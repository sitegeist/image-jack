<?php

namespace Sitegeist\ImageJack\Processor;

use Sitegeist\ImageJack\Interfaces\Modifier;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageJackProcessor extends LocalImageProcessor
{
    protected array $imageModifier = [];

    public function __construct()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['imageModifier'])
            && (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['imageModifier']))) {
            $this->imageModifier = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['imageModifier'];
        }
    }

    /**
     * @inheritDoc
     */
    public function processTask(TaskInterface $task): void
    {
        parent::processTask($task);
        $processedFile = $task->getTargetFile();

        foreach ($this->imageModifier as $name => $className) {
            /** @var Modifier $imageModifier */
            $imageModifier = GeneralUtility::makeInstance($className, $processedFile);
            if ($imageModifier->canProcessImage()) {
                $imageModifier->processFile();
            }
        }

        $task->setExecuted(true);
    }
}
