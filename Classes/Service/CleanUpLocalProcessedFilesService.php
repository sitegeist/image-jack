<?php

declare(strict_types=1);

namespace Sitegeist\ImageJack\Service;

use Sitegeist\ImageJack\Templates\ConverterInterface;
use TYPO3\CMS\Lowlevel\Service\CleanUpLocalProcessedFilesService as CoreCleanUpLocalProcessedFilesService;

final class CleanUpLocalProcessedFilesService extends CoreCleanUpLocalProcessedFilesService
{
    public function __construct(
        private readonly CoreCleanUpLocalProcessedFilesService $decoratedService
    ) {
    }

    public function getFilesToClean(int $limit = 0, bool $fullReset = false): array
    {
        $files = $this->decoratedService->getFilesToClean($limit, $fullReset);
        $imageJackExtensions = $this->getImageJackExtensions();

        if ($imageJackExtensions === []) {
            return $files;
        }

        return array_values(array_filter(
            $files,
            static fn(\SplFileInfo $file): bool => !in_array(
                strtolower($file->getExtension()),
                $imageJackExtensions,
                true
            )
        ));
    }

    private function getImageJackExtensions(): array
    {
        $extensions = [];
        $templates = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'] ?? [];
        $configuration = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack'] ?? [];

        foreach ($templates as $templateClass) {
            if (!is_string($templateClass)
                || !is_a($templateClass, ConverterInterface::class, true)
                || !method_exists($templateClass, 'getTargetFileExtension')
            ) {
                continue;
            }

            $extension = ltrim(strtolower($templateClass::getTargetFileExtension()), '.');
            if ((bool)($configuration[$extension]['active'] ?? false)) {
                $extensions[] = $extension;
            }
        }

        return array_values(array_unique($extensions));
    }
}
