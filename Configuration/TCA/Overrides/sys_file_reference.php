<?php

defined('TYPO3') or die;

$newFields = [
    'tx_imagejack_excluded' => [
        'exclude' => true,
        'label' => 'LLL:EXT:image_jack/Resources/Private/Language/backend.xlf:sys_file_reference.tx_imagejack_excluded',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'default' => 0
        ],
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newFields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'sys_file_reference',
    'imagejackPalette',
    'tx_imagejack_excluded'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_reference',
    '--palette--;;imagejackPalette',
    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE
);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'imageoverlayPalette',
        '--linebreak--,tx_imagejack_excluded',
        'after:crop'
    );
}
