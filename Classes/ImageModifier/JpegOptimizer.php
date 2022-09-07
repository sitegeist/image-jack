<?php

namespace Sitegeist\ImageJack\ImageModifier;

use Sitegeist\ImageJack\Interfaces\Modifier;
use TYPO3\CMS\Core\Log\LogLevel;

class JpegOptimizer extends AbstractModifier implements Modifier
{

    public function canProcessImage(): bool
    {
         return ($this->image->getMimeType() === 'image/jpeg');
    }

    public function processFile(): void
    {
        $buffer = shell_exec('jpegoptim -o -p -P --strip-all --all-progressive ' . escapeshellarg($this->imagePath));
        if (!empty($buffer)) {
            $this->logger->writeLog(trim($buffer), LogLevel::INFO);
        }
    }
}
