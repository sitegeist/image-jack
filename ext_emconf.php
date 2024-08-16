<?php
$EM_CONF['image_jack'] = [
    'title' => 'Image Jack',
    'description' => 'Jack of all trades concerning image optimization. Also introduces the usage of next-gen-image-formats',
    'category' => 'misc',
    'author' => 'Thorsten Schramm',
    'author_email' => 'extensions@sitegeist.de',
    'author_company' => 'sitegeist media solutions GmbH',
    'state' => 'beta',
    'uploadfolder' => false,
    'clearCacheOnLoad' => true,
    'version' => '0.11.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.9.99',
            'php' => '8.1.0-8.3.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Sitegeist\\ImageJack\\' => 'Classes'
        ]
    ],
];
