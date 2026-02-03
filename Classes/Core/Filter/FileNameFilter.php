<?php

declare(strict_types=1);

namespace Sitegeist\ImageJack\Core\Filter;

use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

final class FileNameFilter
{
    /**
     * Remove files from file lists matching the configured pattern,
     * i.e. files ending in .suffix.webp, but not exclusively in .webp.
     */
    public static function filterFilesByPattern(
        string $itemName,
        string $itemIdentifier,
        string $parentIdentifier = '',
        array $additionalInformation = [],
        ?DriverInterface $driverInstance = null
    ): int {
        $pattern = self::getPattern();
        if (null !== $pattern && 1 === \preg_match($pattern, $itemIdentifier)) {
            return -1;
        }

        return 1;
    }

    public static function getPattern(): ?string
    {
        $pattern = (string) $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['hideFilesPattern'] ?? '/\.(jpe?g|png|gif)\.webp$/i';
        // Test validity
        try {
            if (empty($pattern) || false === \preg_match($pattern, '')) {
                return null;
            }
        } catch (\Throwable) {
            return null;
        }

        return $pattern;
    }
}
