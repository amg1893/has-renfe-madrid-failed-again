<?php

namespace App\Controller;

use App\Business\TwitterBusiness;
use App\Entity\HashtagStatus;
use App\Entity\Latest;
use App\Util\TwitterClient;
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
        $hashtags = array_map(function ($hashtag) {
            return $hashtag->toArray();
        }, $hashtags);
        if ($this->request->headers->get('Accept') === 'application/json') {
            return $this->json([
                'hashtags' => $hashtags
            ]);
        }
        return $this->render('index.html.twig', [
            'hashtags' => $hashtags
        ]);
    }

    /**
     * @Route("/update", name="update")
     */
    public function update()
    {
        $this->logger->info('Initializing Twitter Business.');
        $business = new TwitterBusiness();
        $this->logger->info('Getting latest tweet.');
        /** @var Latest $latest */
        $latest = $this->getDoctrine()->getRepository(Latest::class)->findAll()[0];
        $hashtags = $this->getDoctrine()->getRepository(HashtagStatus::class)->findAll();
        $tempHashtags = [];
        /** @var \App\Entity\HashtagStatus $hashtag */
        foreach ($hashtags as $hashtag) {
            $tempHashtags[$hashtag->getHashtag()] = $hashtag;
        }
        $hashtags = $tempHashtags;
        $tweets = $business->getCercaniasTweets($latest->getLastId());
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($tweets as $tweet) {
            $tweetHashtags = array_map(function ($value) {return $value->text;}, $tweet->entities->hashtags);
            $hashtagsToWatch = array_map(function ($value) {return $value->getHashtag();}, $hashtags);
            if ($hashes = array_intersect($hashtagsToWatch, $tweetHashtags)) {
                foreach ($hashes as $hash) {
                    $hashtags[$hash]->setLastId($tweet->id);
                    $hashtags[$hash]->setDateTweet($tweet->created_at);
                }
            }
        }
        /** @var \App\Entity\HashtagStatus $hashtag */
        foreach ($hashtags as $hashtag) {
            $hashtag->updateTime();
            $entityManager->persist($hashtag);
        }
        $lastTweet = end($tweets);
        $latest->setLastId($lastTweet->id);
        $latest->setDateTweet($lastTweet->created_at);
        $entityManager->persist($latest);

        return $app->json(['result' => true]);
    }
}
