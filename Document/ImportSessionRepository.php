<?php

namespace KTD\ImporterBundle\Document;

abstract class ImportSessionRepository
{
    abstract public function getLastUnfinished();
}