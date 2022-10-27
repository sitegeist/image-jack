<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Templates;

use Sitegeist\ImageJack\Utility\LoggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\ProcessedFile;
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
     * @var array
     */
    protected array $extensionConfiguration;

    public function __construct(ProcessedFile $image)
    {
        $this->image = $image;
        $this->imagePath = rtrim(Environment::getPublicPath(), '/') . '/' . $image->getPublicUrl();
        $this->logger = GeneralUtility::makeInstance(LoggerUtility::class);
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('image_jack');
    }
}
