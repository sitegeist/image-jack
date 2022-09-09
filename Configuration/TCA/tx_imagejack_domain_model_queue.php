<?php

return [
    'ctrl' => [
        'title' => 'Queue',
        'label' => 'identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => false,
        'delete' => 'deleted',
    ],
    'columns' => [
        'storage' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file.storage',
            'config' => [
                'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'sys_file_storage',
                'maxitems' => 1,
            ],
        ],
        'identifier' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file.identifier',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
            ],
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'identifier']
    ],
    'palettes' => [
        '1' => ['showitem' => '']
    ]
];
