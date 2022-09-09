<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use Sitegeist\ImageJack\Templates\TemplateInterface;
use TYPO3\CMS\Core\Log\LogLevel;

class PngTemplate extends AbstractTemplate implements TemplateInterface
{

    public function canProcessImage(): bool
    {
         return ($this->image->getMimeType() === 'image/png') || ($this->image->getMimeType() === 'image/gif');
    }

    public function processFile(): void
    {
        $buffer = shell_exec("optipng -o3 -strip all " . escapeshellarg($this->imagePath) . " 2>&1");
        if (!empty($buffer)) {
            $this->logger->writeLog(trim($buffer), LogLevel::INFO);
        }
    }
}
