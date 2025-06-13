import React, { ReactElement } from 'react';
import { createRoot, Root } from 'react-dom/client';
import appConfig from '@rankyMedia/config';
import { on } from '@rankyMedia/helpers/event';
import App from './App';
import { MediaManagerModal, MediaManager, MediaFormPreview } from './index';

const collectedRoots = new Set<Root>()

const Render = (component: ReactElement, container: HTMLElement): Root => {
  const root = createRoot(container);
  root.render(component);
  return root
}

const renderMediaFormPreview = (formSelect: HTMLDivElement): Root => {
  const isMultipleSelection = formSelect.getAttribute('data-multiple-selection') === 'true'
    || formSelect.getAttribute('data-multiple-selection') === '1';
  const fieldId = formSelect.getAttribute('data-field-id');
  const previewJustification = formSelect.getAttribute('data-preview-justification') || 'center';
  const buttonJustification = formSelect.getAttribute('data-button-justification') || 'flex-end';

  return Render(
    <App
      selectionMode
      title={formSelect.getAttribute('data-title') || ''}
      multipleSelection={isMultipleSelection}
      apiPrefix={formSelect.getAttribute('data-api-prefix') || ''}
      targetRef={formSelect}
    >
      <MediaFormPreview
        fieldId={fieldId}
        previewJustification={previewJustification}
        buttonJustification={buttonJustification}
        predefinedData={(formSelect.parentElement.querySelector(`input#${fieldId}`) as HTMLInputElement)?.value || null}
      />
    </App>,
    formSelect,
  )
}

/**
 * Render MediaFormPreview component for each form type
 */
const renderMediaFormPreviews = () => {
  document.querySelectorAll<HTMLDivElement>('.ranky-media-form-type__content')
    .forEach((formSelect) => {
      collectedRoots.add(renderMediaFormPreview(formSelect))
    });
};

/**
 * Add and render MediaManagerModal component
 */
const renderMediaManagerModal = (event: Event) => {
  const element = event.target as HTMLElement;
  event.preventDefault();
  const isMultipleSelection = element.getAttribute('data-multiple-selection') === 'true'
        || element.getAttribute('data-multiple-selection') === '1';
  const modal = document.createElement('div');
  modal.classList.add('wrapper-ranky-media-modal');
  document.body.appendChild(modal);
  Render(
    <App
      selectionMode
      title={element.getAttribute('data-title') || ''}
      multipleSelection={isMultipleSelection}
      apiPrefix={element.getAttribute('data-api-prefix') || ''}
      targetRef={element}
    >
      <MediaManagerModal
        onCloseModal={() => {
          modal.remove();
        }}
      />
    </App>,
    modal,
  );
};

/**
 * MutationObserver for MediaFormPreview
 * Render MediaFormPreview component for each form type when a new form type is added.
 * Example: Symfony form collection
 */
const MediaFormPreviewObserver = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (mutation.type === 'childList') {
      mutation.addedNodes.forEach((node) => {
        const rankyMediaFormElement: HTMLDivElement|null = node instanceof HTMLElement && node.querySelector('.ranky-media-form-type__content');
        if (rankyMediaFormElement) {
          collectedRoots.add(renderMediaFormPreview(rankyMediaFormElement))
        }
      });
    }
  });
});



// should be safe, I guess
on(document, '.ranky-media-open-modal', 'click', (event: Event) => {
  renderMediaManagerModal(event)
})


const initMediaManager = (container: HTMLElement): Root => Render(
  <App apiPrefix={container.getAttribute('data-api-prefix') || ''}>
    <MediaManager />
  </App>,
  container,
)


const bindComponents = (): void => {
  MediaFormPreviewObserver.observe(document.body, {
    childList: true,
    subtree: true,
  })

  renderMediaFormPreviews()

  const mediaManagerEl: HTMLElement = document.querySelector(appConfig.root_class)
  if(mediaManagerEl !== null) {
    collectedRoots.add(initMediaManager(mediaManagerEl))
  }
}

const unbindComponents = (): void => {
  MediaFormPreviewObserver.disconnect()

  for(const root of collectedRoots.values()) {
    root.unmount()
  }

  collectedRoots.clear()
}

window.RankyMediaBundle = {
  unbindComponents,
  bindComponents,
  initMediaManager,
  renderMediaManagerModal,
  mediaFormPreviewObserver: {
    observe: (): void => MediaFormPreviewObserver.observe(document.body, {
      childList: true,
      subtree: true,
    }),
    disconnect: () => MediaFormPreviewObserver.disconnect(),
  },
  renderMediaFormPreview,
}
