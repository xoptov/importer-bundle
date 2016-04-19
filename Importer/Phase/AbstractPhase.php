<?php

namespace KTD\ImporterBundle\Importer\Phase;

abstract class AbstractPhase implements PhaseInterface
{
    protected $priority;

    /**
     * {@inheritdoc}
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }
}