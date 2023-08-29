<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

interface ConverterInterface
{
    public static function getTargetMimeType(): string;

    public static function getTargetFileExtension(): string;

    public static function getPriority(): int;
}
