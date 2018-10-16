<?php

namespace App\Controller;

use App\Entity\HashtagStatus;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $this->logger->info('Obtaining data.');
        $hashtags = $this->getDoctrine()->getRepository(HashtagStatus::class)->findAll();
        if ($this->request->headers->get('Accept') === 'application/json') {
            return $this->json([
                'hashtags' => $hashtags
            ]);
        }
        return $this->render('index.html', [
            'hashtags' => $hashtags
        ]);
    }
}
