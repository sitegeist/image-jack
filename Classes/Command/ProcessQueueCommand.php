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
                'Amout of items to process at once',
                '20'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $limit = (int)$input->getOption('limit');

        $templateRunner = GeneralUtility::makeInstance(TemplateRunner::class, null);
        $processedFileRepository = GeneralUtility::makeInstance(ProcessedFileRepository::class);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_processedfile')
            ->createQueryBuilder();

        $result = $queryBuilder
            ->select('uid')
            ->from('sys_file_processedfile')
            ->where(
                $queryBuilder->expr()->eq('tx_imagejack_processed', $queryBuilder->createNamedParameter(0))
            )
            ->setMaxResults($limit)
            ->orderBy('tstamp', 'ASC')
            ->executeQuery();

        $itemCount = $result->rowCount();

        if ($itemCount > 0) {
            $progress = $io->createProgressBar($itemCount);

            while ($row = $result->fetchAssociative()) {
                $processedFile = $processedFileRepository->findByUid($row['uid']);

                if (is_a($processedFile, ProcessedFile::class)) {
                    $templateRunner->setProcessedFile($processedFile);
                    $templateRunner->run();
                }

                $queryBuilder
                    ->update('sys_file_processedfile')
                    ->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($row['uid']))
                    )
                    ->set('tx_imagejack_processed', time())
                    ->executeStatement();

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
