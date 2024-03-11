<?php

namespace Sitegeist\ImageJack\Helper;

use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RequestHelper
{
    public static function checkForMimeTypeInAcceptHeader(ServerRequest $request, string $mimeType): bool
    {
        if ($request->hasHeader('accept')) {
            $accept = $request->getHeader('accept');
            $acceptHeader = [];
            if (!empty($accept[0])) {
                $acceptHeader = GeneralUtility::trimExplode(',', $accept[0]);
            }

            return in_array($mimeType, $acceptHeader);
        }

        return false;
    }
}
