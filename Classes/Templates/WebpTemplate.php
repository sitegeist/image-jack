<?php
declare(strict_types = 1);

namespace Sitegeist\ImageJack\Templates;

use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Imaging\GifBuilder;

class WebpTemplate extends AbstractTemplate implements TemplateInterface, ConverterInterface
{
    public function isAvailable(): bool
    {
        return in_array($this->image->getMimeType(), $this->getSupportedMimeTypes()) && $this->isActive();
    }

    public function getSupportedMimeTypes(): array
    {
        $mimeTypes = [];
        foreach ($this->extensionConfiguration['webp']['mimeTypes'] as $key => $value) {
            if ($value) {
                $mimeTypes[] = 'image/' . $key;
            }
        }

        return $mimeTypes;
    }

    public function isActive(): bool
    {
        return (bool)$this->extensionConfiguration['webp']['active'];
    }

    public static function getTargetMimeType(): string
    {
        return 'image/webp';
    }

    public static function getTargetFileExtension(): string
    {
        return '.webp';
    }

    public static function getPriority(): int
    {
        return 10;
    }

    public function processFile(): void
    {
        $converter = $this->extensionConfiguration['webp']['converter'] ?: 'im';
        $options = $this->extensionConfiguration['webp']['options'] ?:
            '-sharpen 1 -quality 75 -define webp:lossless=false -define webp:method=6';
        $targetFile = GeneralUtility::tempnam($this->image->getNameWithoutExtension(), '.webp');

        switch ($converter) {
            case 'gd':
                $buffer = (string)$this->convertImageUsingGd($options, $targetFile);
                break;

            case 'ext':
                $buffer = $this->convertImageUsingExt($options, $targetFile);
                break;

            default:
                $buffer = $this->convertImageUsingIm($options, $targetFile);
                break;
        }

        try {
            $this->storage->addFile(
                $targetFile,
                $this->image->getParentFolder(), // @phpstan-ignore-line
                $this->image->getName() . '.webp',
                DuplicationBehavior::REPLACE
            );
        } catch (\TypeError $e) {
            // Ignore TypeError => T3 doesn't like writing directly in a processed folder
        }

        if (!empty($buffer)) {
            $this->logger->writeLog(trim($buffer), LogLevel::INFO);
        }
    }

    /**
     * @param string $options
     * @param string $targetFile
     * @return string
     */
    protected function convertImageUsingIm(string $options, string $targetFile): string
    {
        $graphicalFunctionsObject = GeneralUtility::makeInstance(GraphicalFunctions::class);
        return $graphicalFunctionsObject->imageMagickExec(
            $this->imagePath,
            $targetFile,
            $options
        );
    }

    /**
     * @param string $quality
     * @param string $targetFile
     * @return bool
     */
    protected function convertImageUsingGd(string $quality, string $targetFile): bool
    {
        if (function_exists('imagewebp') && defined('IMG_WEBP') && (imagetypes() & IMG_WEBP) === IMG_WEBP) {
            $version = GeneralUtility::makeInstance(Typo3Version::class);
            if ($version->getMajorVersion() == 13) {
                $graphicalFunctionsObject = GeneralUtility::makeInstance(GifBuilder::class);// @phpstan-ignore-line
            } else {
                $graphicalFunctionsObject = GeneralUtility::makeInstance(GraphicalFunctions::class);
            }
            $image = $graphicalFunctionsObject->imageCreateFromFile($this->imagePath);// @phpstan-ignore-line
            // Convert CMYK to RGB
            if (!imageistruecolor($image)) {
                imagepalettetotruecolor($image);
            }

            return imagewebp($image, $targetFile, (int)$quality);
        }
        $this->logger->writeLog('Webp is not supported by your GD version', LogLevel::ERROR);

        return false;
    }

    /**
     * @param string $command
     * @param string $targetFile
     * @return string
     */
    protected function convertImageUsingExt(string $command, string $targetFile): string
    {
        if (substr_count($command, '%s') !== 2) {
            $this->logger->writeLog('Please use two placeholders in your command', LogLevel::ERROR);
        }
        $binary = explode(' ', $command)[0];
        if (!is_executable($binary)) {
            $this->logger->writeLog(
                sprintf('Binary "%s" is not executable! Please use the full path to the binary.', $binary),
                LogLevel::ERROR
            );
            return 'Error! See log for further information.';
        }

        $this->logger->writeLog($command, LogLevel::INFO);
        return CommandUtility::exec(sprintf(
            escapeshellcmd($command),
            CommandUtility::escapeShellArgument($this->imagePath),
            CommandUtility::escapeShellArgument($targetFile)
        ) . ' >/dev/null 2>&1');
    }
}
