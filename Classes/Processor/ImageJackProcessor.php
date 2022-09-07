<?php

namespace Sitegeist\ImageJack\Processor;

use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageJackProcessor extends LocalImageProcessor
{

    /**
     * @var array
     */
    private array $logLevelHierarchy = [
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
    private array $logLevelOutput;

    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @var string
     */
    protected string $logLevel;

    /**
     * @inheritDoc
     */
    public function processTask(TaskInterface $task): void
    {

        parent::processTask($task);

        $this->log = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
        $this->logLevel = 'info';
        $this->logLevelOutput = $this->getLogLevelHierarchy();
        $processedFile = $task->getTargetFile();

        $projectPath = getenv('TYPO3_PATH_ROOT').'/';
        $imagePath = $projectPath.$processedFile->getPublicUrl();
        if (($processedFile->getMimeType() === 'image/jpeg')) {
            $buffer = shell_exec("jpegoptim -o -p -P --strip-all --all-progressive " . escapeshellarg($imagePath));
            if (!empty($buffer)) {
                $this->writeLog(trim($buffer), LogLevel::INFO);
            }
        } else if (($processedFile->getMimeType() === 'image/png') || ($processedFile->getMimeType() === 'image/gif')) {
            $buffer = shell_exec("optipng -o3 -strip all " . escapeshellarg($imagePath) . " 2>&1");
            if (!empty($buffer)) {
                $buffer = explode("\n", $buffer);
                $this->writeLog(trim($buffer[0]) . ': ' . trim($buffer[count($buffer) - 4]) . ' -> ' . trim($buffer[count($buffer) - 3]), LogLevel::INFO);
            }
        }
        $task->setExecuted(true);
    }

    /**
     * Writes the messages into the logfile
     *
     * @param string $message
     * @param string $level
     * @return void
     */
    private function writeLog(string $message, string $level): void
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                if (in_array(LogLevel::EMERGENCY, $this->logLevelOutput)) {
                    $this->log->emergency($message);
                }
                break;
            case LogLevel::ALERT:
                if (in_array(LogLevel::ALERT, $this->logLevelOutput)) {
                    $this->log->alert($message);
                }
                break;
            case LogLevel::CRITICAL:
                if (in_array(LogLevel::CRITICAL, $this->logLevelOutput)) {
                    $this->log->critical($message);
                }
                break;
            case LogLevel::ERROR:
                if (in_array(LogLevel::ERROR, $this->logLevelOutput)) {
                    $this->log->error($message);
                }
                break;
            case LogLevel::NOTICE:
                if (in_array(LogLevel::NOTICE, $this->logLevelOutput)) {
                    $this->log->notice($message);
                }
                break;
            case LogLevel::INFO:
                if (in_array(LogLevel::INFO, $this->logLevelOutput)) {
                    $this->log->info($message);
                }
                break;
            case LogLevel::DEBUG:
                if (in_array(LogLevel::DEBUG, $this->logLevelOutput)) {
                    $this->log->debug($message);
                }
                break;
            default:
                if (in_array(LogLevel::WARNING, $this->logLevelOutput)) {
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
    private function getLogLevelHierarchy(): array
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
