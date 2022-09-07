<?php
defined('TYPO3_MODE') || die();

call_user_func(function () {

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['Sitegeist']['ImageJack']['writerConfiguration'] = [
        // configuration for ERROR level log entries
        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
            // add a FileWriter
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                // configuration for the writer
                'logFileInfix' => 'image_jack'
            ],
        ]
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['processors']['ImageJackProcessor'] = [
        'className' => Sitegeist\ImageJack\Processor\ImageJackProcessor::class,
        'before' => ['LocalImageProcessor'],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['imageModifier']['jpegOptimizer'] =
        \Sitegeist\ImageJack\ImageModifier\JpegOptimizer::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['imageModifier']['pngOptimizer'] =
        \Sitegeist\ImageJack\ImageModifier\PngOptimizer::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['imageModifier']['webpConverter'] =
        \Sitegeist\ImageJack\ImageModifier\WebpConverter::class;
});
