<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use Sitegeist\ImageJack\Templates\TemplateInterface;
use TYPO3\CMS\Core\Log\LogLevel;

class JpegTemplate extends AbstractTemplate implements TemplateInterface
{
    public function isActive(): bool
    {
        // TODO: Implement isActive() method.
        return false;
    }

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
