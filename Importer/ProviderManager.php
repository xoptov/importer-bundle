<?php

namespace KTD\ImporterBundle\Importer;

use KTD\ImporterBundle\Importer\Phase\PhaseInterface;

class ProviderManager
{
    /** @var ProviderInterface[] */
    private $providers;

    /** @var array */
    private $phases;

    /**
     * ImporterManager constructor.
     */
    public function __construct()
    {
        $this->providers = array();
        $this->phases = array();
    }

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @param string $provider
     * @param PhaseInterface $phase
     */
    public function addPhase($provider, PhaseInterface $phase)
    {
        if (array_key_exists($provider, $this->phases)) {
            array_push($this->phases[$provider], $phase);
        } else {
            $this->phases[$provider] = array($provider => $phase);
        }
    }

    /**
     * @param $name
     * @return ProviderInterface
     */
    public function getProvider($name)
    {
        if (count($this->providers)) {
            foreach ($this->providers as $provider) {
                if ($provider->getName() === $name) {
                    $this->preparePhases($provider);
                    return $provider;
                }
            }
        }

        return null;
    }

    /**
     * @param ProviderInterface $provider
     */
    protected function preparePhases(ProviderInterface $provider)
    {
        $name = $provider->getName();
        if (array_key_exists($name, $this->phases)) {
            foreach ($this->phases[$name] as $phase) {
                $provider->addPhase($phase);
            }
        }
    }
}