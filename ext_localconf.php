<?php

use Psr\Log\LogLevel;
use Sitegeist\ImageJack\Templates\JpegTemplate;
use Sitegeist\ImageJack\Templates\PngTemplate;
use Sitegeist\ImageJack\Templates\WebpTemplate;
use Sitegeist\ImageJack\Xclass\AmazonS3Driver;
use Sitegeist\ImageJack\Xclass\LocalDriver;
use TYPO3\CMS\Core\Log\Writer\FileWriter;

defined('TYPO3') || die();

call_user_func(function () {

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['Sitegeist']['ImageJack']['writerConfiguration'] = [
        // configuration for ERROR level log entries
        LogLevel::DEBUG => [
            // add a FileWriter
            FileWriter::class => [
                // configuration for the writer
                'logFileInfix' => 'image_jack'
            ],
        ]
    ];

    if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['useLiveProcessing'])
        && $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['useLiveProcessing']) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['processors']['ImageJackProcessor'] = [
            'className' => Sitegeist\ImageJack\Processor\ImageJackProcessor::class,
            'before' => ['LocalImageProcessor']
        ];
    }

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']['jpegOptimizer'] =
        JpegTemplate::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']['pngOptimizer'] =
        PngTemplate::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']['webpConverter'] =
        WebpTemplate::class;

    if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['useFallbackDriver'])) {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredDrivers']['Local'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredDrivers']['Local']['class']] = [
                'className' => LocalDriver::class
            ];
        }

        if (isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredDrivers']['AusDriverAmazonS3'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredDrivers']['AusDriverAmazonS3']['class']] = [
                'className' => AmazonS3Driver::class
            ];
        }
    }
});
