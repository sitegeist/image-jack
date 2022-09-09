<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Runner;

use Sitegeist\ImageJack\Templates\TemplateInterface;
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
                /** @var TemplateInterface $templates */
                $template = GeneralUtility::makeInstance($className, $this->processedFile);
                if ($template->canProcessImage()) {
                    $template->processFile();
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
