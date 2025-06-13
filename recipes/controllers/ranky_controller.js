import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    /** @type {{ unmount: () => void }} */
    #reactRoot

    connect()
    {
        this.#reactRoot = window.RankyMediaBundle.renderMediaFormPreview(this.element)
    }

    disconnect()
    {
        this.#reactRoot.unmount()
    }
}