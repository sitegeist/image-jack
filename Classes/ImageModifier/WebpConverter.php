<?php

namespace Sitegeist\ImageJack\ImageModifier;

use Sitegeist\ImageJack\Interfaces\Modifier;
use TYPO3\CMS\Core\Log\LogLevel;

class WebpConverter extends AbstractModifier implements Modifier
{

    public function canProcessImage(): bool
    {
        $mimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];

         return (in_array($this->image->getMimeType(), $mimeTypes));
    }

    public function processFile(): void
    {
        $buffer = shell_exec('convert -quality 75 -define webp:lossless=false -define webp:method=6 ' .
            escapeshellarg($this->imagePath) . ' ' . escapeshellarg($this->imagePath . '.webp'));
        if (!empty($buffer)) {
            $this->logger->writeLog(trim($buffer), LogLevel::INFO);
        }
    }
}
