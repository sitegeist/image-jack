<?php

namespace Sitegeist\ImageJack\Xclass;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ProcessedFile;

class ImageService extends \TYPO3\CMS\Extbase\Service\ImageService
{
    public function applyProcessingInstructions($image, array $processingInstructions): ProcessedFile
    {
        if (is_a($image, FileReference::class)) {
            if ($image->hasProperty('tx_imagejack_excluded') && $image->getProperty('tx_imagejack_excluded') === 1) {
                $processingInstructions['tx_imagejack_excluded'] = true;
            }
        }
        return parent::applyProcessingInstructions($image, $processingInstructions);
    }
}
