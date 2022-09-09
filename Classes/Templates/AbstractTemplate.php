<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use Sitegeist\ImageJack\Utility\LoggerUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractTemplate
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
