<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PngTemplate extends AbstractTemplate implements TemplateInterface
{
    public function isAvailable(): bool
    {
        return (($this->image->getMimeType() === 'image/png') || ($this->image->getMimeType() === 'image/gif') &&
            ($this->extensionConfiguration['png']['active']));
    }

    public function processFile(): void
    {
        $binary = $this->extensionConfiguration['png']['path'];
        if (!is_executable($binary)) {
            $this->logger->writeLog(
                sprintf('Binary "%s" is not executable! Please use the full path to the binary.', $binary),
                LogLevel::ERROR
            );
            return;
        }

        $buffer = CommandUtility::exec(sprintf(
            escapeshellcmd($binary . ' -o3 -strip all %s'),
            CommandUtility::escapeShellArgument($this->imagePath)
        ) . ' >/dev/null 2>&1');

        GeneralUtility::fixPermissions($this->imagePath);

        if (!empty($buffer)) {
            $this->logger->writeLog(trim($buffer), LogLevel::INFO);
        }
    }
}
