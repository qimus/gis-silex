<?php

$loader = require_once __DIR__ . "/../vendor/autoload.php";
$loader->add('app', __DIR__ . '/../');

$app = new Silex\Application();


//register providers
$app->register(
    new \Mongo\Silex\Provider\MongoServiceProvider(),
    [
        'mongo.connections' => [
            'default' => [
                'server' => 'mongodb://localhost:27017',
                'options' => ['connect' => true]
            ]
        ]
    ]
);

$app->error(function (\app\exceptions\ApiException $e, $code) {
    $errors = ['message' => $e->getMessage(), 'code' => $e->getCode()];

    return new \Symfony\Component\HttpFoundation\JsonResponse(
        ['errors' => $errors],
        $e->getCode()
    );
});


$app->mount('/', new \app\controllers\IndexController());
$app->mount('/firms', new app\controllers\FirmsController());
$app->mount('/buildings', new app\controllers\BuildingsController());
$app->mount('/rubrics', new app\controllers\RubricsController());

$app->run();

