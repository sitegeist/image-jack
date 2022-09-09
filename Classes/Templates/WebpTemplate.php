<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use Sitegeist\ImageJack\Templates\TemplateInterface;
use TYPO3\CMS\Core\Log\LogLevel;

class WebpTemplate extends AbstractTemplate implements TemplateInterface
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
