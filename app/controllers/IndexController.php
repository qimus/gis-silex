<?php

namespace app\controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * Class IndexController
 * @package app\controllers
 * @author Denis Dyadyun <sysadm85@gmail.com>
 */
class IndexController implements ControllerProviderInterface
{
    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app->json($this->getApiScheme());
        });

        return $controllers;
    }

    /**
     * Метод возвращает схему ресурсов
     *
     * @return array
     */
    public function getApiScheme()
    {
        return [
            'title' => 'Схема api gis системы',
            'resources' => [
                [
                    'resource' => '/firms',
                    'description' => 'Работа со справочником фирм'
                ],
                [
                    'resource' => '/buildings',
                    'description' => 'Работа со справочником зданий'
                ],
                [
                    'resource' => '/rubrics',
                    'description' => 'Работа со справочником рубрик '
                ]
            ]
        ];
    }
}
