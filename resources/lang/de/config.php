<?php

return [
    'disk'  => [
        'label'        => 'Upload Disk', // @todo: decide if we want to translate the expression "disk" or leave it as it is
        'instructions' => 'Wählen Sie eine &laquo;Disk&raquo; in welche die Date hochgeladen wird.'
    ],
    'path'  => [
        'label'        => 'Upload Pfad',
        'instructions' => 'Geben Sie einen Pfad an, in den die Datei hochgeladen werden soll, oder lassen Sie das Feld leer um die Datei in das Wurzelverzeichnis der Disk hochzuladen.<br>Wenn der Pfad nicht existiert, wird er während dem Upload erstellt.'
    ],
    'image' => [
        'label'        => 'Nur Bilder',
        'instructions' => 'Soll der Upload auf Bilder beschränkt werden?'
    ],
    'mimes' => [
        'label'        => 'Erlaubte Dateien',
        'instructions' => 'Geben Sie die Erweiterung der erlaubten Dateien an. Wenn keine Erweiterungen eingegeben werden, ist jeder Dateityp für den Upload erlaubt.'
    ],
    'max'   => [
        'label'        => 'Maximale Grösse',
        'instructions' => 'Geben Sie die maximal zulässige Dateigrösse in <strong>Megabyte</strong> an.<br>Der Standardwert ist die vom System vorgegebene maximal zulässige Dateigrösse für Uploads.'
    ]
];
