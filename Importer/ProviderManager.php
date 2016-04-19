<?php

namespace KTD\ImporterBundle\Importer;

use KTD\ImporterBundle\Importer\Phase\PhaseInterface;
use KTD\ImporterBundle\Importer\Provider\ProviderInterface;

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
    }

    /**
     * @param $name
     * @param ProviderInterface $provider
     */
    public function addProvider($name, ProviderInterface $provider)
    {
        $this->providers[$name] = $provider;
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
        if (isset($this->providers[$name]) && $this->providers[$name] instanceof ProviderInterface) {
            /** @var ProviderInterface $provider */
            $provider = $this->providers[$name];

            if (array_key_exists($name, $this->phases)) {
                foreach ($this->phases[$name] as $phase) {
                    $provider->addPhase($phase);
                }
            }

            return $provider;
        }

        return null;
    }
}