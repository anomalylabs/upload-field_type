<?php

return [
    'disk'  => [
        'type'     => 'anomaly.field_type.relationship',
        'required' => true,
        'config'   => [
            'related' => 'Anomaly\FilesModule\Disk\DiskModel'
        ]
    ],
    'path'  => [
        'type'  => 'anomaly.field_type.text',
        'rules' => [
            'regex:/^[a-zA-Z0-9_\s\/]+$/'
        ]
    ],
    'image' => [
        'type' => 'anomaly.field_type.boolean'
    ],
    'mimes' => [
        'type' => 'anomaly.field_type.tags'
    ],
    'max'   => [
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
            'min'           => 1
        ]
    ]
];
