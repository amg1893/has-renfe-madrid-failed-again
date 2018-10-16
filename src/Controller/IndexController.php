<?php

namespace App\Controller;

use App\Entity\HashtagStatus;
use App\Repository\HashtagStatusRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(LoggerInterface $logger)
    {
        $logger->info('Obtaining data.');
        $hashtags = $this->getDoctrine()->getRepository(HashtagStatusRepository::class)->findAll();
        if ($this->get('headers')->get('Accept') === 'application/json') {
            return $this->json([
                'hashtags' => $hashtags
            ]);
        }
        return $this->render('index', [
            'hashtags' => $hashtags
        ]);
    }
}
