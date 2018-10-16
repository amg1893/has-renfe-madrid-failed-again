<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger = null;
    /** @var \Symfony\Component\HttpFoundation\Request $request */
    protected $request = null;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    }
}