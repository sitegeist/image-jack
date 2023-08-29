<?php
namespace Sitegeist\ImageJack\Xclass;

use Sitegeist\ImageJack\Templates\ConverterInterface;

trait DriverTrait
{
    public function getPublicUrl($identifier)
    {
        if ($GLOBALS['TYPO3_REQUEST']->hasHeader('accept')) {
            $acceptHeader = strtolower($GLOBALS['TYPO3_REQUEST']->getHeader('accept')[0]);
            $templates = $this->sortTemplates($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']);

            /** @var ConverterInterface $template */
            foreach ($templates as $template) {
                if (in_array(ConverterInterface::class, class_implements($template))) {
                    $targetMimeType = $template::getTargetMimeType();
                    $targetFileExtension = $template::getTargetFileExtension();

                    if (substr($identifier, -5) !== $targetFileExtension) {
                        if (str_contains($acceptHeader, $targetMimeType)) {
                            if ($this->fileExists($identifier . $targetFileExtension)) {
                                $identifier .= $targetFileExtension;
                            }
                        }
                    }
                }
            }
        }

        return parent::getPublicUrl($identifier);
    }

    protected function sortTemplates($templates): array
    {
        usort($templates, function ($a, $b) {
            if (!method_exists($a, 'getPriority') && (!method_exists($b, 'getPriority'))) {
                return 0;
            } elseif (!method_exists($a, 'getPriority')) {
                return -1;
            } elseif (!method_exists($b, 'getPriority')) {
                return 1;
            } else {
                return $a->getPriority() - $b->getPriority();
            }
        });

        return $templates;
    }
}
