<?php
declare(strict_types = 1);

namespace Sitegeist\ImageJack\Templates;

use Sitegeist\ImageJack\Utility\LoggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractTemplate
{
    /**
     * @var ProcessedFile
     */
    protected ProcessedFile $image;

    /**
     * @var string
     */
    protected string $imagePath;

    /**
     * @var LoggerUtility
     */
    protected LoggerUtility $logger;

    /**
     * @var ResourceStorage
     */
    protected ResourceStorage $storage;

    /**
     * @var array
     */
    protected array $extensionConfiguration;

    public function __construct(ProcessedFile $image, LoggerUtility $logger)
    {
        $this->image = $image;
        $this->storage = $image->getStorage();
        $this->imagePath = $this->storage->getFileForLocalProcessing($image);
        $this->logger = $logger;
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('image_jack');
    }
}
