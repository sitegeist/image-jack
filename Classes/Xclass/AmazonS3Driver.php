<?php

namespace Sitegeist\ImageJack\Xclass;

class AmazonS3Driver extends \AUS\AusDriverAmazonS3\Driver\AmazonS3Driver {
    public function getPublicUrl($identifier)
    {
        return parent::getPublicUrl($identifier);
    }
}
