<?php

require '../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv('../');
$dotenv->load();

$app = new Silex\Application();
$app['debug'] = getenv('DEBUG');

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Register MySQL
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => getenv('DATABASE_DB'),
        'user' => getenv('DATABASE_USER'),
        'password' => getenv('DATABASE_PASS'),
        'host' => getenv('DATABASE_HOST'),
    ],
));

// Our web handlers

$app->get('/', function () use ($app) {
    $app['monolog']->addDebug('logging output.');
    $hashtags = $app['db']->fetchAll('SELECT * FROM hashtag_status');
    return $app['twig']->render('index.twig', ['hashtags' => $hashtags]);
});

$app->get('/update', function () use ($app) {
    $app['monolog']->addDebug('connecting to twitter.');
    $conn = new Abraham\TwitterOAuth\TwitterOAuth(getenv('CONSUMER_KEY'), getenv('CONSUMER_SECRET'), getenv('ACCESS_TOKEN'), getenv('ACCESS_SECRET'));
    $app['monolog']->addDebug('getting latest ID.');
    $lastTweet = $app['db']->fetchAssoc('SELECT last_id, date_tweet FROM latest');
    $lastTweetID = $lastTweet['last_id'];
    $lastTweetDate = Datetime::createFromFormat('M j H:i:s P Y', $lastTweet['date_tweet']);
    $app['monolog']->addDebug('getting hashtags to follow.');
    $hashtags = $app['db']->fetchAll('SELECT * FROM hashtag_status');
    $tempHashtags = [];
    foreach ($hashtags as $hashtag) {
        $tempHashtags[$hashtag['hashtag']] = $hashtag;
    }
    $hashtags = $tempHashtags;
    $app['monolog']->addDebug('getting tweets.');
    $content = $conn->get('statuses/user_timelines', [
        'screen_name' => 'cercaniasmadrid',
        'since_id' => $lastTweetID,
        'exclude_replies' => true,
        'include_rts' => false,
    ]);
    foreach ($content as $tweet) {
        $dateTweet = Datetime::createFromFormat('M j H:i:s P Y', $tweet['created_at']);
        if ($dateTweet > $lastTweetDate) {
            $lastTweetID = $tweet['id'];
            $lastTweetDate = $dateTweet;
        }
        if ($hashes = array_intersect($hashtagsArray, $tweet['entities']['hashtags'])) {
            foreach ($hashes as $hash) {
                $hashtags[$hash]['last_id'] = $lastTweetID;
                $hashtags[$hash]['date_tweet'] = $lastTweetDate->format('M j H:i:s P Y');
            }
        }
    }
    $now = date('Y-m-d H:i:s');
    foreach ($hashtags as $hashtag => $data) {
        $query = $app['db']->prepare('UPDATE hashtag_status SET last_id = :lastId, date_tweet = :dateTweet, update_date = :now WHERE hashtag = :hashtag');
        $query->bindValue('lastId', $data['last_id']);
        $query->bindValue('dateTweet', $data['date_tweet']);
        $query->bindValue('now', $now);
        $query->bindValue('hashtag', $hashtag);
        $query->execute();
    }

    $app->json(['result' => true]);
});

$app->run();
