<?php
declare(strict_types = 1);

namespace Sitegeist\ImageJack\Utility;

use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LoggerUtility
{
    /**
     * @var array
     */
    protected array $logLevelHierarchy = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    /**
     * @var array
     */
    protected array $logLevelOutput;

    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @var string
     */
    protected string $logLevel;

    /**
     * @var array
     */
    protected array $extensionConfiguration;

    public function __construct($logLevel = 'info')
    {
        $this->log = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
        $this->logLevel = $logLevel;
        $this->logLevelOutput = $this->getLogLevelHierarchy();
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('image_jack');
    }

    /**
     * Writes the messages into the logfile
     *
     * @param string $message
     * @param string $level
     */
    public function writeLog(string $message, string $level): void
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                if (in_array(LogLevel::EMERGENCY, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['emergencies'])) {
                    $this->log->emergency($message);
                }
                break;
            case LogLevel::ALERT:
                if (in_array(LogLevel::ALERT, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['alerts'])) {
                    $this->log->alert($message);
                }
                break;
            case LogLevel::CRITICAL:
                if (in_array(LogLevel::CRITICAL, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['critical'])) {
                    $this->log->critical($message);
                }
                break;
            case LogLevel::ERROR:
                if (in_array(LogLevel::ERROR, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['errors'])) {
                    $this->log->error($message);
                }
                break;
            case LogLevel::NOTICE:
                if (in_array(LogLevel::NOTICE, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['notices'])) {
                    $this->log->notice($message);
                }
                break;
            case LogLevel::INFO:
                if (in_array(LogLevel::INFO, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['infos'])) {
                    $this->log->info($message);
                }
                break;
            case LogLevel::DEBUG:
                if (in_array(LogLevel::DEBUG, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['debugs'])) {
                    $this->log->debug($message);
                }
                break;
            default:
                if (in_array(LogLevel::WARNING, $this->logLevelOutput) &&
                    ($this->extensionConfiguration['logging']['warnings'])) {
                    $this->log->warning($message);
                }
                break;
        }
    }

    /**
     * Writes the messages into the logfile
     *
     * @return array
     */
    protected function getLogLevelHierarchy(): array
    {
        $returnArray = [];
        foreach ($this->logLevelHierarchy as $level) {
            $returnArray[] = $level;
            if ($level === $this->logLevel) {
                break;
            }
        }
        return $returnArray;
    }
}
