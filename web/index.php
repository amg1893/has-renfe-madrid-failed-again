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

set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($app) {
    $app['monolog']->addError($errstr . ' (' . $errno . ') - ' . $errfile . ':' . $errline);
});

set_exception_handler(function (\Throwable $exception) use ($app) {
    $app['monolog']->addError($exception->getMessage() . ' (' . $exception->getCode() . ') - ' . $exception->getFile() . ':' . $exception->getLine());
});

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

$app->get('/', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $app['monolog']->addDebug('logging output.');
    $hashtags = $app['db']->fetchAll('SELECT * FROM hashtag_status');
    $ret = ['hashtags' => $hashtags];
    if ($request->getContentType() === 'application/json') {
        return $app->json($ret);
    }
    return $app['twig']->render('index.twig', $ret);
});

$app->get('/update', function () use ($app) {
    $app['monolog']->addDebug('connecting to twitter.');
    $conn = new Abraham\TwitterOAuth\TwitterOAuth(getenv('CONSUMER_KEY'), getenv('CONSUMER_SECRET'), getenv('ACCESS_TOKEN'), getenv('ACCESS_SECRET'));
    $app['monolog']->addDebug('getting latest ID.');
    $lastTweet = $app['db']->fetchAssoc('SELECT last_id, date_tweet FROM latest');
    $lastTweetID = $lastTweet['last_id'];
    $lastTweetDate = new Datetime($lastTweet['date_tweet']);
    $app['monolog']->addDebug('getting hashtags to follow.');
    $hashtags = $app['db']->fetchAll('SELECT * FROM hashtag_status');
    $tempHashtags = [];
    foreach ($hashtags as $hashtag) {
        $tempHashtags[$hashtag['hashtag']] = $hashtag;
    }
    $hashtags = $tempHashtags;
    $app['monolog']->addDebug('getting tweets.');
    $content = $conn->get('statuses/user_timeline', [
        'screen_name' => 'cercaniasmadrid',
        'since_id' => $lastTweetID,
        'exclude_replies' => false,
        'include_rts' => false,
        'count' => 200,
    ]);
    if (property_exists($content, 'errors')) {
        return $app->json(['result' => false]);
    }
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
        return new Datetime($a->created_at) <=> new Datetime($b->created_at);
    });
    foreach ($content as $tweet) {
        $tweetHashtags = array_map(function ($value) {return $value->text;}, $tweet->entities->hashtags);
        if ($hashes = array_intersect(array_keys($hashtags), $tweetHashtags)) {
            foreach ($hashes as $hash) {
                $hashtags[$hash]['last_id'] = $tweet->id;
                $hashtags[$hash]['date_tweet'] = $tweet->created_at;
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
    $lastTweet = end($content);
    $lastTweetQuery = $app['db']->prepare('UPDATE latest SET last_id = :lastId, date_tweet = :dateTweet');
    $lastTweetQuery->bindValue('lastId', $lastTweet->id);
    $lastTweetQuery->bindValue('dateTweet', $lastTweet->created_at);
    $lastTweetQuery->execute();

    return $app->json(['result' => true]);
});

$app->run();
