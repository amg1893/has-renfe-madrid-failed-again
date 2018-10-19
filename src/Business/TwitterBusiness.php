<?php

namespace App\Business;

use App\Constants\TweetStatus;
use App\Util\TwitterClient;

class TwitterBusiness
{
    /** @var TwitterClient $client */
    protected $client = null;

    /** @var array $positiveWords */
    protected $positiveWords = [];

    /** @var array $negativeWords */
    protected $negativeWords = [];

    public function __construct()
    {
        $this->client = new TwitterClient();
        $this->positiveWords = [
            'reparad',
            'subsanad',
            'recuper',
        ];
        $this->negativeWords = [
            'problema',
            'demora',
            'incidencia',
            'accidente',
            'catenarias',
        ];
    }

    public function getCercaniasTweets($lastTweetID)
    {
        $content = $this->client->getConn()->get('statuses/user_timeline', [
            'screen_name' => 'cercaniasmadrid',
            'since_id' => $lastTweetID,
            'exclude_replies' => false,
            'include_rts' => false,
            'count' => 500,
        ]);
        $content = array_filter($content, function ($tweet) {
            $flag = true;
            foreach ($tweet->entities->user_mentions as $userMention) {
                if ($userMention->name !== 'cercaniasmadrid') {
                    $flag = false;
                    break;
                }
            }
            return $flag;
        });
        usort($content, function ($a, $b) {
            return new \Datetime($a->created_at) <=> new \Datetime($b->created_at);
        });

        return $content;
    }

    public function analyzeTweet($text): int
    {
        foreach ($this->positiveWords as $positiveWord) {
            if (strpos($text, $positiveWord) !== false) {
                return TweetStatus::RIGHT;
            }
        }
        foreach ($this->negativeWords as $negativeWord) {
            if (strpos($text, $negativeWord) !== false) {
                return TweetStatus::WRONG;
            }
        }
        return TweetStatus::UNKWN;
    }
}