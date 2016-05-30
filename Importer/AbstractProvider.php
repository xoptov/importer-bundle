<?php

namespace Xoptov\ImporterBundle\Importer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Xoptov\ImporterBundle\Document\ImportSessionRepository;
use Xoptov\ImporterBundle\Importer\Phase\PhaseInterface;
use Xoptov\ImporterBundle\Importer\Phase\PhasesStack;
use Xoptov\ImporterBundle\Model\AbstractImportSession;
use Psr\Log\LoggerInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /** @var PhasesStack */
    protected $phasesStack;

    /** @var DocumentManager */
    protected $documentManager;

    /** @var string */
    protected $sessionClassName;

    /** @var LoggerInterface */
    protected $logger;
    
    /**
     * AbstractProvider constructor.
     */
    public function  __construct(DocumentManager $documentManager, $sessionClassName, LoggerInterface $logger)
    {
        $this->phasesStack = new PhasesStack();
        // Dependencies
        $this->documentManager = $documentManager;
        $this->sessionClassName = $sessionClassName;
        $this->logger = $logger;
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
        $repository = $this->documentManager->getRepository($this->sessionClassName);
        
        return $repository->getLastUnfinished($this->getName());
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

        $this->documentManager->persist($session);

        return $session;
    }

    /**
     * @param AbstractImportSession $session
     */
    protected function doneProcessingSession(AbstractImportSession $session, $status, $startTime)
    {
        $session->setStatus($status)
            ->setSpentSeconds(time() - $startTime);

        $this->documentManager->flush();
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

        $startTime = time();

        /** @var PhaseInterface $phase */
        while ($phase = $this->phasesStack->pull()) {
            try {
                $phase->process();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $session->setPhase($phase->getName());
                $this->doneProcessingSession($session, AbstractImportSession::STATUS_FAILED, $startTime);

                return;
            }
        }

        $this->doneProcessingSession($session, AbstractImportSession::STATUS_PROCESSED, $startTime);
    }
}
