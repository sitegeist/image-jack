<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use TYPO3\CMS\Core\Resource\ProcessedFile;

interface TemplateInterface
{
    public function canProcessImage(): bool;

    public function processFile(): void;
}
