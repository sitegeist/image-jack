<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Command;

use Sitegeist\ImageJack\Runner\TemplateRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ProcessQueueCommand extends Command
{

    protected function configure()
    {
        $this
            ->setHelp(
                'Process the image jack queue'
            )
            ->addOption(
                'limit',
                'l',
                InputOption::VALUE_REQUIRED,
                'Number of images that are processed consecutively per run',
                '20'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $limit = (int)$input->getOption('limit');

        $safeProcessing = isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['image_jack']['useSafeProcessing']);

        $templateRunner = GeneralUtility::makeInstance(TemplateRunner::class, null);
        $processedFileRepository = GeneralUtility::makeInstance(ProcessedFileRepository::class);
        $version = new Typo3Version();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_processedfile')
            ->createQueryBuilder();
        if (version_compare($version->getVersion(), '11.0', '<')) {
            $result = $queryBuilder
                ->select('uid', 'storage', 'identifier')
                ->from('sys_file_processedfile')
                ->where(
                    $queryBuilder->expr()->eq('tx_imagejack_processed', $queryBuilder->createNamedParameter(0))
                )
                ->setMaxResults($limit)
                ->orderBy('tstamp', 'ASC')
                ->execute();
        } else {
            $result = $queryBuilder
                ->select('uid', 'storage', 'identifier')
                ->from('sys_file_processedfile')
                ->where(
                    $queryBuilder->expr()->eq('tx_imagejack_processed', $queryBuilder->createNamedParameter(0))
                )
                ->setMaxResults($limit)
                ->orderBy('tstamp', 'ASC')
                ->executeQuery();
        }

        $itemCount = $result->rowCount();

        if ($itemCount > 0) {
            $progress = $io->createProgressBar($itemCount);

            while ($row = $result->fetchAssociative()) {
                if ($row['storage'] !== 0) {
                    if (!empty($row['identifier']) || ($safeProcessing === false)) {
                        $processedFile = $processedFileRepository->findByUid($row['uid']);

                        if (is_a($processedFile, ProcessedFile::class)) {
                            $templateRunner->setProcessedFile($processedFile);
                            $templateRunner->run();
                        }
                    }
                }

                if (version_compare($version->getVersion(), '11.0', '<')) {
                    $queryBuilder
                        ->update('sys_file_processedfile')
                        ->where(
                            $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($row['uid']))
                        )
                        ->set('tx_imagejack_processed', time())
                        ->execute();
                } else {
                    $queryBuilder
                        ->update('sys_file_processedfile')
                        ->where(
                            $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($row['uid']))
                        )
                        ->set('tx_imagejack_processed', time())
                        ->executeStatement();
                }

                $progress->advance();
            }

            $progress->finish();
            $io->writeln('');
            $io->success($itemCount . ' image(s) processed');
        } else {
            $io->success('No image found');
        }

        return Command::SUCCESS;
    }
}
