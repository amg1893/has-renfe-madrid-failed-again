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
            'hashtags' => $hashtags,
            'version' => $this->getVersion()
        ]);
    }

    /**
     * @Route("/update", name="update")
     */
    public function update()
    {
        $this->logger->info('Initializing Twitter Business.');
        $business = new TwitterBusiness();
        $this->logger->info('Getting latest tweet and hashtags.');
        /** @var Latest $latest */
        $latest = $this->getDoctrine()->getRepository(Latest::class)->findAll()[0];
        $hashtags = $this->getDoctrine()->getRepository(HashtagStatus::class)->findAll();
        $tempHashtags = [];
        /** @var \App\Entity\HashtagStatus $hashtag */
        foreach ($hashtags as $hashtag) {
            $tempHashtags[$hashtag->getHashtag()] = $hashtag;
        }
        $hashtags = $tempHashtags;
        $this->logger->info('Getting tweets.');
        $tweets = $business->getCercaniasTweets($latest->getLastId());
        $entityManager = $this->getDoctrine()->getManager();

        $this->logger->info('Searching tweets with hashtags.');
        foreach ($tweets as $tweet) {
            $tweetHashtags = array_map(function ($value) {return $value->text;}, $tweet->entities->hashtags);
            $hashtagsToWatch = array_values(array_map(function ($value) {return $value->getHashtag();}, $hashtags));
            if ($hashes = array_intersect($hashtagsToWatch, $tweetHashtags)) {
                foreach ($hashes as $hash) {
                    $hashtags[$hash]->setLastId($tweet->id);
                    $hashtags[$hash]->setDateTweet($tweet->created_at);
                    $hashtags[$hash]->setStatus($business->analyzeTweet($tweet->text));
                }
            }
        }
        $this->logger->info('Updating hashtags in database.');
        /** @var \App\Entity\HashtagStatus $hashtag */
        foreach ($hashtags as $hashtag) {
            $hashtag->updateTime();
            $entityManager->merge($hashtag);
        }
        $this->logger->info('Updating latest tweet.');
        if (\count($tweets) > 0) {
            $lastTweet = end($tweets);
            $latest->setLastId($lastTweet->id);
            $latest->setDateTweet($lastTweet->created_at);
            $entityManager->merge($latest);
        }

        $entityManager->flush();

        return $this->json(['result' => true]);
    }

    protected function getVersion(): string
    {
        $composerData = json_decode(file_get_contents($this->kernel->getRootDir().'/../composer.json'), true);
        return $composerData['version'] ?? '';
    }
}
