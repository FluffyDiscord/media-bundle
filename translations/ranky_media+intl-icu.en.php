<?php

declare(strict_types=1);

return [
    'url'                          => 'URL',
    'title'                        => 'Image and file management',
    'open_upload_dashboard'        => 'Open the media upload panel',
    'on_remove'                    => 'File deleted successfully',
    'on_update'                    => 'File updated correctly',
    'dropzone'                     => 'Drop files to start the upload process',
    'pagination_info'              => 'Showing {current} of {total} media items',
    'pagination_button_load'       => 'Load more',
    'file'                         => 'File',
    'file_type'                    => 'File Type',
    'type'                         => 'Type',
    'mime'                         => 'MIME',
    'date'                         => 'Date',
    'dimensions'                   => 'Dimensions',
    'size'                         => 'Size',
    'by'                           => 'by',
    'created_at'                   => 'Created at',
    'updated_at'                   => 'Updated at',
    'breakpoints'                  => 'Versions',
    'filters_user'                 => 'All users',
    'filters_date'                 => 'All dates',
    'filters_mime_type'            => 'All types',
    'filters_search'               => 'Search...',
    'filters_view_list'            => 'List view',
    'filters_view_grid'            => 'Grid view',
    'filters_sort_asc'             => 'Sort ascending',
    'filters_sort_desc'            => 'Sort descending',
    'bulk_actions_title'           => 'Batch actions',
    'bulk_actions_delete'          => 'Delete permanently',
    'bulk_actions_delete_prompt'   => 'Are you sure you want to delete the selected media ({value})?',
    'bulk_actions_error_no_action' => 'No action corresponds to the {value} value',
    'bulk_actions_error_no_select' => 'You must select at least one media to perform a batch action.',
    'form_name'                    => 'Name',
    'form_alt'                     => 'Alternative Text',
    'form_title'                   => 'Title',
    'form_save'                    => 'Save',
    'delete'                       => 'Delete',
    'modal_title'                  => 'File details {file_name} <small>id: {id}</small>',
    'open_new_window'              => 'Open in a new window',
    'modal_close'                  => 'Close',
    'modal_next'                   => 'Next',
    'modal_prev'                   => 'Prev',
    'form_type' => [
        'open_selection_button'        => 'Select media',
        'clean_selection_button'        => 'Clear selection',
    ],
    'selection_mode'               => [
        'zero_element'      => 'No item selected',
        'one_element'       => '1 item selected',
        'multiple_elements' => '{length} selected items',
        'clean'             => 'Clear selection',
        'insert'            => 'Insert selection',
    ],
    'swal'                         =>
        [
            'confirm_delete'       => 'Are you sure you want to delete this media?',
            'successfully'         => 'Action done correctly',
            'undefined_attributes' => 'The following attributes have not been defined: <b>{attributes}</b>',
            'delete_title'         => 'Are you sure you want to delete this media?',
            'delete_text'          => 'This action is irreversible',
            'confirm_button'       => 'Accept',
            'cancel_button'        => 'Cancel',
        ],
    'errors'                       =>
        [
            'not_found'   => 'No media found with ID {id}',
            'bad_request' => 'No value found for the {field} parameter',
            'not_files'   => 'The file could not be uploaded. Not a valid file or the $_FILES variable is empty.',
        ],
];
