<?php

namespace Sitegeist\ImageJack\ImageModifier;

use Sitegeist\ImageJack\Interfaces\Modifier;
use TYPO3\CMS\Core\Log\LogLevel;

class PngOptimizer extends AbstractModifier implements Modifier
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
