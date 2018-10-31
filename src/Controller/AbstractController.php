<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;
    /** @var \Symfony\Component\HttpFoundation\Request $request */
    protected $request;
    /** @var KernelInterface $kernel */
    protected $kernel;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
        $this->request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    }
}