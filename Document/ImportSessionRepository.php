<?php

namespace Xoptov\ImporterBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Xoptov\ImporterBundle\Model\AbstractImportSession;

abstract class ImportSessionRepository extends DocumentRepository
{
    /**
     * @param string $provider
     * @return AbstractImportSession|null
     */
    abstract public function getLastUnfinished($provider);
}