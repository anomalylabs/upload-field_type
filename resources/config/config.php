<?php

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\UploadFieldType\UploadFieldType;

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
            'default_value' => function (UploadFieldType $type) {
                $post = $type->getSize('post_max_size');
                $file = $type->getSize('upload_max_filesize');

                return $file > $post ? $post : $file;
            },
            'max'           => function (UploadFieldType $type) {
                $post = $type->getSize('post_max_size');
                $file = $type->getSize('upload_max_filesize');

                return $file > $post ? $post : $file;
            },
            'min'           => 1,
        ],
    ],
];
