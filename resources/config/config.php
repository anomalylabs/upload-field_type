<?php

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;

return [
    'folder' => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'options' => function (FolderRepositoryInterface $folders) {
                return $folders
                    ->all()
                    ->pluck('name', 'id')
                    ->all();
            },
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
