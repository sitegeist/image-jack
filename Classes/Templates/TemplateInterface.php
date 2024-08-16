<?php
declare(strict_types = 1);

namespace Sitegeist\ImageJack\Templates;

interface TemplateInterface
{
    public function isAvailable(): bool;

    public function getSupportedMimeTypes(): array;

    public function isActive(): bool;

    public function processFile(): void;
}
