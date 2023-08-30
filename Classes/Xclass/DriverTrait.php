<?php
namespace Sitegeist\ImageJack\Xclass;

use Sitegeist\ImageJack\Templates\ConverterInterface;

trait DriverTrait
{
    public function getPublicUrl($identifier): ?string
    {
        $fileInfo = $this->getFileInfoByIdentifier($identifier, ['mimetype']);
        $templates = $this->sortAndFilterTemplates($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']);
        $mimeTypes = $this->extractMimetypesFromTemplates($templates);

        /** @var ConverterInterface $template */
        foreach ($templates as $template) {
            $targetFileExtension = $template::getTargetFileExtension();

            if (!empty($fileInfo['mimetype']) && !in_array($fileInfo['mimetype'], $mimeTypes)) {
                if ($this->fileExists($identifier . $targetFileExtension)) {
                    $identifier .= $targetFileExtension;
                }
            }
        }

        return parent::getPublicUrl($identifier);
    }

    protected function sortAndFilterTemplates(array $templates): array
    {
        $templates = array_filter($templates, function ($template) {
            return in_array(ConverterInterface::class, class_implements($template));
        });

        usort($templates, function ($a, $b) {
            return $a::getPriority() - $b::getPriority();
        });

        return $templates;
    }

    protected function extractMimetypesFromTemplates(array $templates): array
    {
        return array_map(function ($template) {
            return $template::getTargetMimeType();
        }, $templates);
    }
}
