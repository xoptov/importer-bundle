<?php

namespace KTD\ImporterBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use KTD\ImporterBundle\Model\AbstractImportSession;

abstract class ImportSessionRepository extends DocumentRepository
{
    /**
     * @param string $provider
     * @return AbstractImportSession|null
     */
    abstract public function getLastUnfinished($provider);
}