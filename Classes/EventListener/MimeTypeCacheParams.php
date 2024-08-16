<?php

declare(strict_types=1);

namespace Sitegeist\ImageJack\EventListener;

use Sitegeist\ImageJack\Helper\RequestHelper;
use Sitegeist\ImageJack\Templates\ConverterInterface;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Frontend\Event\BeforePageCacheIdentifierIsHashedEvent;

#[AsEventListener(
    identifier: 'image-jack/mimetype-cache-params',
    method: '__invoke'
)]
final readonly class MimeTypeCacheParams
{
    public function __invoke(BeforePageCacheIdentifierIsHashedEvent $event): void
    {
        $request = $event->getRequest();
        $params = $event->getPageCacheIdentifierParameters();
        if ($request->hasHeader('accept')) {
            $templates = array_filter($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['image_jack']['templates'], function ($template) {
                return in_array(ConverterInterface::class, class_implements($template));
            });
            foreach ($templates as $template) {
                $targetMimeType = $template::getTargetMimeType();
                if (RequestHelper::checkForMimeTypeInAcceptHeader($request, $targetMimeType)) {
                    $params['hashParameters']['accepts'][$targetMimeType] = true;
                }
            }

            $event->setPageCacheIdentifierParameters($params);
        }
    }
}
