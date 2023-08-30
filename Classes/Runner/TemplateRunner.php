<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Runner;

use Sitegeist\ImageJack\Utility\LoggerUtility;
use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TemplateRunner
{
    protected array $templates = [];

    /**
     * @var ProcessedFile|null
     */
    protected ?ProcessedFile $processedFile;

    /**
     * @var LoggerUtility
     */
    protected LoggerUtility $logger;

    public function __construct(?ProcessedFile $processedFile)
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'])
            && (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']))) {
            $this->templates = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'];
        }

        $this->processedFile = $processedFile;
        $this->logger = GeneralUtility::makeInstance(LoggerUtility::class);
    }

    public function run(): void
    {
        if (is_a($this->processedFile, ProcessedFile::class)) {
            foreach ($this->templates as $className) {
                try {
                    $template = GeneralUtility::makeInstance($className, $this->processedFile, $this->logger);
                    if ($template->isAvailable()) {
                        $template->processFile();
                    }
                } catch (\Exception $e) {
                    $this->logger->writeLog($className . ' has failed! Error: ' . $e->getMessage(), LogLevel::ERROR);
                }
            }
        }
    }

    /**
     * @return ProcessedFile|null
     */
    public function getProcessedFile(): ?ProcessedFile
    {
        return $this->processedFile;
    }

    /**
     * @param ProcessedFile|null $processedFile
     */
    public function setProcessedFile(?ProcessedFile $processedFile): void
    {
        $this->processedFile = $processedFile;
    }
}
