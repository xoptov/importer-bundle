<?php

namespace KTD\ImporterBundle\Importer\Provider;

use KTD\ImporterBundle\Importer\Phase\PhaseInterface;
use KTD\ImporterBundle\Model\AbstractImportSession;

interface ProviderInterface
{
    /**
     * @param PhaseInterface $phase
     * @return void
     */
    public function addPhase(PhaseInterface $phase);

    /**
     * @return void
     */
    public function execute();

    /**
     * @return string
     */
    public function getName();
}