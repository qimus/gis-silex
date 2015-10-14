<?php

namespace app\controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BuildingController
 * @package app\controllers
 * @author Denis Dyadyun <sysadm85@gmail.com>
 */
class BuildingsController implements ControllerProviderInterface
{
    const DEFAULT_LIMIT = 10;
    const DEFAULT_OFFSET = 0;

    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'app\controllers\BuildingController::find');

        return $controllers;
    }

    /**
     * Поиск зданий
     *
     * @param Request     $request
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function find(Request $request, Application $app)
    {
        /** @var \MongoClient $mongo */
        $mongo = $app['mongo']['default'];

        $criteria = [];

        $limit = (int) $request->get('limit', self::DEFAULT_LIMIT);
        $offset = (int) $request->get('offset', self::DEFAULT_OFFSET);

        $cursor = $mongo->selectCollection('gis', 'buildings')
            ->find($criteria, ['_id' => false])
            ->limit($limit)
            ->skip($offset)
            ->sort(['id' => 1]);

        $result = iterator_to_array($cursor);

        $meta = [
            'count' => $mongo->selectCollection('gis', 'buildings')->count(),
            'limit' => $limit,
            'offset' => $offset
        ];

        return $app->json(
            [
                'metadata' => $meta,
                'buildings' => array_values($result)
            ]
        );
    }
}
