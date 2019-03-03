<?php

return [
    'folder' => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'handler' => \Anomaly\UploadFieldType\Support\Config\FolderHandler::class,
        ],
    ],
    'image'  => [
        'type' => 'anomaly.field_type.boolean',
    ],
    'mimes'  => [
        'type' => 'anomaly.field_type.tags',
    ],
    'max'    => [
        'type'     => 'anomaly.field_type.integer',
        'required' => true,
        'config'   => [
            'default_value' => max_upload_size(),
            'max'           => max_upload_size(),
            'min'           => 1,
        ],
    ],
];
