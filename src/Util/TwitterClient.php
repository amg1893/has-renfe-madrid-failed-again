<?php

namespace App\Util;

use \Abraham\TwitterOAuth\TwitterOAuth;

class TwitterClient
{
    /** @var TwitterOAuth $conn */
    protected $conn = null;

    public function __construct()
    {
        $this->conn = new TwitterOAuth(
            getenv('CONSUMER_KEY'),
            getenv('CONSUMER_SECRET'),
            getenv('ACCESS_TOKEN'),
            getenv('ACCESS_SECRET')
        );
    }

    public function getConn(): TwitterOAuth
    {
        return $this->conn;
    }
}