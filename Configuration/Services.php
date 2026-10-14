<?php

declare(strict_types=1);

use Sitegeist\ImageJack\Command\ProcessQueueCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services
        ->load('Sitegeist\\ImageJack\\', '../Classes/*')
        ->exclude('../Classes/Service/CleanUpLocalProcessedFilesService.php');

    $services
        ->set(ProcessQueueCommand::class)
        ->tag('console.command', [
            'command' => 'jack:process',
            'description' => 'Process the image jack queue',
        ]);

    $coreService = 'TYPO3\\CMS\\Lowlevel\\Service\\CleanUpLocalProcessedFilesService';
    if (class_exists($coreService)) {
        $services
            ->set('Sitegeist\\ImageJack\\Service\\CleanUpLocalProcessedFilesService')
            ->decorate($coreService)
            ->arg('$decoratedService', service('.inner'));
    }
};
