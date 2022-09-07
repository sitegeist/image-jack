<?php

namespace Sitegeist\ImageJack\ImageModifier;

use Sitegeist\ImageJack\Utility\LoggerUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractModifier
{
    protected ProcessedFile $image;

    protected string $imagePath;

    protected LoggerUtility $logger;

    public function __construct(ProcessedFile $image)
    {
        $this->image = $image;
        $this->imagePath = Environment::getPublicPath() . $image->getPublicUrl();
        $this->logger = GeneralUtility::makeInstance(LoggerUtility::class);
    }
}
