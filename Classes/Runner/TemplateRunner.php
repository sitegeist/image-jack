<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Runner;

use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TemplateRunner
{
    protected array $templates = [];

    protected ?ProcessedFile $processedFile;

    public function __construct(?ProcessedFile $processedFile)
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'])
            && (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates']))) {
            $this->templates = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'];
        }

        $this->processedFile = $processedFile;
    }

    public function run(): void
    {
        if (is_a($this->processedFile, ProcessedFile::class)) {
            foreach ($this->templates as $className) {
                try {
                    $template = GeneralUtility::makeInstance($className, $this->processedFile);
                    if ($template->isAvailable()) {
                        $template->processFile();
                    }
                } catch (\Exception $e) {
                    error_log($className . ' has failed! Error: ' . $e->getMessage());
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
