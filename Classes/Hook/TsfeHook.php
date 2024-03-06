<?php

namespace Sitegeist\ImageJack\Hook;

use Sitegeist\ImageJack\Helper\RequestHelper;
use Sitegeist\ImageJack\Templates\ConverterInterface;
use TYPO3\CMS\Core\Http\ServerRequest;

class TsfeHook
{
    public function postProcessHashBase($params): void
    {
        /** @var ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
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
        }
    }
}
