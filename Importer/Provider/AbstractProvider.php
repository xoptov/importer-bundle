<?php

namespace KTD\ImporterBundle\Importer\Provider;

use KTD\ImporterBundle\Document\ImportSessionRepository;
use KTD\ImporterBundle\Importer\Phase\PhaseInterface;
use KTD\ImporterBundle\Importer\Phase\PhasesStack;
use KTD\ImporterBundle\Model\AbstractImportSession;
use Symfony\Bridge\Doctrine\ManagerRegistry;

abstract class AbstractProvider implements ProviderInterface
{
    /** @var PhasesStack */
    protected $phasesStack;

    /** @var ManagerRegistry */
    protected $managerRegistry;

    /** @var string */
    protected $sessionClassName;
    
    /**
     * AbstractProvider constructor.
     */
    public function  __construct(ManagerRegistry $managerRegistry, $sessionClassName)
    {
        $this->phasesStack = new PhasesStack();
        // Dependencies
        $this->managerRegistry = $managerRegistry;
        $this->sessionClassName = $sessionClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function addPhase(PhaseInterface $phase)
    {
        $this->phasesStack->push($phase);
    }

    /**
     * @return AbstractImportSession|null
     */
    protected function getUnfinishedSession()
    {
        /** @var ImportSessionRepository $repository */
        $repository = $this->managerRegistry->getRepository($this->sessionClassName);
        
        return $repository->getLastUnfinished();
    }
    
    /**
     * @return AbstractImportSession
     */
    protected function createSession()
    {
        /** @var AbstractImportSession $session */
        $session = new $this->sessionClassName();
        $session
            ->setPhase($this->phasesStack->current()->getName())
            ->setProvider($this->getName());

        $this->managerRegistry->getManager()->persist($session);

        return $session;
    }

    /**
     * @param AbstractImportSession $session
     */
    protected function endSession(AbstractImportSession $session)
    {
        $session->setStatus(AbstractImportSession::STATUS_PROCESSED);
        $this->managerRegistry->getManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $session = $this->getUnfinishedSession();

        if (!$session) {
            $session = $this->createSession();
        } else {
            $this->phasesStack->rewind($session->getPhase());
        }

        /** @var PhaseInterface $phase */
        foreach ($this->phasesStack->pull() as $phase) {
            $phase->process();
        }

        $this->endSession($session);
    }
}
