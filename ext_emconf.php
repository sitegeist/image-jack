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
    'version' => '0.9.2',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-12.9.99',
            'php' => '7.4.0-8.1.99'
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
