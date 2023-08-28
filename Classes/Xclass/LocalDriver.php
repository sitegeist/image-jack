<?php

namespace Sitegeist\ImageJack\Xclass;

use Sitegeist\ImageJack\Templates\ConverterInterface;

class LocalDriver extends \TYPO3\CMS\Core\Resource\Driver\LocalDriver {
    public function getPublicUrl($identifier)
    {
        if ($GLOBALS['TYPO3_REQUEST']->hasHeader('accept')) {
            $acceptHeader = strtolower($GLOBALS['TYPO3_REQUEST']->getHeader('accept')[0]);

            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'] as $template) {
                if (in_array(ConverterInterface::class, class_implements($template))) {
                    $targetMimeType = $template::getTargetMimeType();
                    $targetFileExtension = $template::getTargetFileExtension();

                    if (substr($identifier, -5) !== $targetFileExtension) {
                        if (str_contains($acceptHeader, $targetMimeType)) {
                            if ($this->fileExists($identifier . $targetFileExtension)) {
                                return parent::getPublicUrl($identifier . $targetFileExtension);
                            }
                        }
                    }
                }
            }
        }

        return parent::getPublicUrl($identifier);
    }
}
