<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

interface TemplateInterface
{
    public function isActive(): bool;

    public function canProcessImage(): bool;

    public function processFile(): void;
}
