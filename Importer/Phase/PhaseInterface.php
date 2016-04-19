<?php

namespace KTD\ImporterBundle\Importer\Phase;

interface PhaseInterface
{
    /**
     * @param int $priority
     * @return void
     */
    public function setPriority($priority);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return void
     */
    public function process();

    /**
     * @return string
     */
    public function getName();
}