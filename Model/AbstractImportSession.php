<?php

namespace KTD\ImporterBundle\Model;

abstract class AbstractImportSession
{
    /** @var int */
    protected $spentSeconds;
    
    /** @var int */
    protected $status;

    /** @var string */
    protected $provider;

    /** @var string */
    protected $phase;

    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_FAILED = 3;

    /**
     * @param int $seconds
     * @return AbstractImportSession
     */
    public function setSpentSeconds($seconds)
    {
        $this->spentSeconds = $seconds;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getSpentSeconds()
    {
        return $this->spentSeconds;
    }

    /**
     * @param $status
     * @return AbstractImportSession
     */
    public function setStatus($status)
    {
        $this->status = $status;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $provider
     * @return AbstractImportSession
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $phase
     * @return AbstractImportSession
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhase()
    {
        return $this->phase;
    }
}