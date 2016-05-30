<?php

namespace Xoptov\ImporterBundle\Importer\Phase;

class PhasesStack
{
    /** @var PhaseInterface[] */
    private $phases;

    /**
     * PhasesStack constructor.
     */
    public function __construct()
    {
        $this->phases = array();
    }

    /**
     * @param PhaseInterface $phase
     */
    public function push(PhaseInterface $phase)
    {
        array_push($this->phases, $phase);
        $this->sort();
    }

    /**
     * @return PhaseInterface
     */
    public function current()
    {
        return current($this->phases);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function rewind($name)
    {
        /** @var PhaseInterface $phase */
        while (list($index, $phase) = each($this->phases)) {
            if ($phase->getName() === $name) {
                return true;
            }
        }
        
        reset($this->phases);
        return false;
    }

    /**
     * @return PhaseInterface
     */
    public function pull()
    {
        return array_pop($this->phases);
    }

    /**
     * @return void
     */
    private function sort()
    {
        if (count($this->phases) > 1) {
            usort($this->phases, function($first, $second){
                /**
                 * @var PhaseInterface $first
                 * @var PhaseInterface $second
                 */
                if ($first->getPriority() === $second->getPriority()) {
                    return 0;
                } elseif ($first->getPriority() > $second->getPriority()) {
                    return 1;
                } else {
                    return -1;
                }
            });
        }
    }
}