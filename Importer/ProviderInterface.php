<?php

namespace Xoptov\ImporterBundle\Importer;

use Xoptov\ImporterBundle\Importer\Phase\PhaseInterface;

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