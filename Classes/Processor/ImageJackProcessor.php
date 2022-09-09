<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Processor;

use Sitegeist\ImageJack\Runner\TemplateRunner;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageJackProcessor extends LocalImageProcessor
{
    /**
     * @inheritDoc
     */
    public function processTask(TaskInterface $task): void
    {
        parent::processTask($task);
        $processedFile = $task->getTargetFile();

        $templateRunner = GeneralUtility::makeInstance(TemplateRunner::class, $processedFile);
        $templateRunner->run();

        $task->setExecuted(true);
    }
}
