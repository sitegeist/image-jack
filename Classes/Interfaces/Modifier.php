<?php

namespace Sitegeist\ImageJack\Interfaces;

use TYPO3\CMS\Core\Resource\ProcessedFile;

interface Modifier
{
    public function canProcessImage(): bool;

    public function processFile(): void;
}
