<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Processor;

use Sitegeist\ImageJack\Domain\Model\Queue;
use Sitegeist\ImageJack\Domain\Repository\QueueRepository;
use Sitegeist\ImageJack\Templates\TemplateInterface;
use Sitegeist\ImageJack\Runner\TemplateRunner;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class ImageJackProcessor extends LocalImageProcessor
{
    protected bool $useQueue = true;

    public function __construct()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['useQueue'])) {
            $this->useQueue = (bool)$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['useQueue'];
        }
    }

    /**
     * @inheritDoc
     */
    public function processTask(TaskInterface $task): void
    {
        parent::processTask($task);
        $processedFile = $task->getTargetFile();

        if ($this->useQueue === true) {
            $queueRepository = GeneralUtility::makeInstance(QueueRepository::class);
            $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
            $queueItem = GeneralUtility::makeInstance(Queue::class);
            $queueItem->setStorage($processedFile->getStorage());
            $queueItem->setIdentifier($processedFile->getIdentifier());
            $queueRepository->add($queueItem);
            $persistenceManager->persistAll();
        } else {
            $templateRunner = GeneralUtility::makeInstance(TemplateRunner::class, $processedFile);
            $templateRunner->run();
        }

        $task->setExecuted(true);
    }
}
