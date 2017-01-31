<?php

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;

return [
    'folder' => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'options' => function (FolderRepositoryInterface $folders) {
                return $folders->all()->pluck('name', 'id')->all();
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
            'default_value' => function () {
                $post = str_replace('M', '', ini_get('post_max_size'));
                $file = str_replace('M', '', ini_get('upload_max_filesize'));

                return $file > $post ? $post : $file;
            },
            'max'           => function () {
                $post = str_replace('M', '', ini_get('post_max_size'));
                $file = str_replace('M', '', ini_get('upload_max_filesize'));

                return $file > $post ? $post : $file;
            },
            'min'           => 1,
        ],
    ],
];
