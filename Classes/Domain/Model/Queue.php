<?php
declare(strict_types=1);

namespace Sitegeist\ImageJack\Domain\Model;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * A image queue item
 */
class Queue extends AbstractValueObject
{
    /**
     * The storage this file is located in
     *
     * @var ResourceStorage|null
     */
    protected $storage;

    /**
     * The identifier of this file to identify it on the storage.
     * On some drivers, this is the path to the file, but drivers could also just
     * provide any other unique identifier for this file on the specific storage.
     *
     * @var string
     */
    protected $identifier;

    /**
     * @return ResourceStorage|null
     */
    public function getStorage(): ?ResourceStorage
    {
        return $this->storage;
    }

    /**
     * @param ResourceStorage|null $storage
     */
    public function setStorage(?ResourceStorage $storage): void
    {
        $this->storage = $storage->getUid();
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }
}
