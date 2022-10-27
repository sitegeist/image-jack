<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

interface TemplateInterface
{
    public function isAvailable(): bool;

    public function processFile(): void;
}
