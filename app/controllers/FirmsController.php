<?php

namespace app\controllers;

use app\exceptions\ApiException;
use app\helpers\ArrayHelper;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FirmController
 * @package app\controllers
 * @author Denis Dyadyun <sysadm85@gmail.com>
 */
class FirmsController implements ControllerProviderInterface
{
    const DEFAULT_LIMIT = 10;
    const DEFAULT_OFFSET = 0;

    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'app\controllers\FirmController::find');
        $controllers->get('/{id}', 'app\controllers\FirmController::view')
            ->assert('id', '\d+');


        return $controllers;
    }

    /**
     * Получение инфы по id
     *
     * @param             $id
     * @param Application $app
     *
     * @return JsonResponse
     * @throws ApiException
     */
    public function view($id, Application $app)
    {
        /** @var \MongoClient $mongo */
        $mongo = $app['mongo']['default'];

        $firm = $mongo->selectCollection('gis', 'firms')
            ->findOne(['id' => (int) $id]);


        if (empty($firm)) {
            throw new ApiException('Not found', Response::HTTP_NOT_FOUND);
        }

        $metadata = [
            'count' => 1,
            'limit' => 1,
            'offset' => 0
        ];

        $firms = $this->_loadAdditional($mongo, [$firm]);

        return $app->json(
            [
                'metadata' => $metadata,
                'firms' => $firms
            ]
        );
    }

    /**
     * Поиск фирм
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

        if ($request->get('building_id')) {
            $criteria['building_id'] = (int)$request->get('building_id');
        }

        if ($request->get('rubric_ids')) {
            $criteria['rubrics'] = [
                '$in' => $this->_getRubrics($mongo, explode(',', $request->get('rubric_ids')))
            ];
        }

        if ($request->get('geo_search') == 'radius' &&
            $request->get('distance') &&
            $request->get('point'))
        {
            $criteria = $this->_addCriteriaByRadius($criteria, $request);
        }

        if ($request->get('geo_search') == 'area' &&
            $request->get('bl_point') &&
            $request->get('tr_point'))
        {
            $criteria = $this->_addCriteriaByArea($criteria, $request);
        }

        if ($request->get('q')) {
            $criteria['title'] = [
                '$regex' =>  new \MongoRegex("/{$request->get('q')}/i"),
                '$options' => 'im'
            ];
        }


        $limit = (int) $request->get('limit', self::DEFAULT_LIMIT);
        $offset = (int) $request->get('offset', self::DEFAULT_OFFSET);

        $cursor = $mongo->selectCollection('gis', 'firms')
            ->find($criteria, ["_id" => false])
            ->limit($limit)
            ->skip($offset)
            ->sort(["id" => 1]);

        $result = iterator_to_array($cursor);

        $result = $this->_loadAdditional($mongo, $result);

        $count = $mongo->selectCollection('gis', 'firms')->count($criteria);

        $metadata = [
            'count' => $count,
            'limit' => $limit,
            'offset' => $offset
        ];

        return $app->json(
            [
                'metadata' => $metadata,
                'firms' => $result
            ]
        );
    }

    /**
     * @param         $criteria
     * @param Request $request
     *
     * @return mixed
     * @throws ApiException
     */
    protected function _addCriteriaByRadius($criteria, Request $request)
    {
        if (false === strpos($request->get('point'), ',')) {
            throw new ApiException('Bad request', Response::HTTP_BAD_REQUEST);
        }

        list($lat, $lon) = array_map('floatval', explode(',', $request->get('point')));
        $maxDistance = $request->get('distance') / 111;
        $criteria['coords'] = [
            '$within' => ['$center' => [[$lat, $lon], $maxDistance]]
        ];

        return $criteria;
    }

    /**
     * @param         $criteria
     * @param Request $request
     *
     * @return mixed
     * @throws ApiException
     */
    protected function _addCriteriaByArea($criteria, Request $request)
    {
        if (false === strpos($request->get('bl_point'), ',') ||
            false === strpos($request->get('tr_point'), ','))
        {
            throw new ApiException('Bad request', Response::HTTP_BAD_REQUEST);
        }
        list($bl_lat, $bl_lon) = array_map('floatval', explode(',', $request->get('bl_point')));
        list($tr_lat, $tr_lon) = array_map('floatval', explode(',', $request->get('tr_point')));
        $criteria['coords'] = [
            '$geoWithin' => [
                '$box' => [
                    [$bl_lat, $bl_lon],
                    [$tr_lat, $tr_lon]
                ]
            ]
        ];

        return $criteria;
    }

    /**
     * Подгрузка связанной инфы
     *
     * @param \MongoClient $mongo
     * @param              $result
     *
     * @return array
     */
    protected function _loadAdditional(\MongoClient $mongo, $result)
    {
        $result = $this->_loadRubrics($mongo, $result);
        $result = $this->_loadBuilding($mongo, $result);

        return $result;
    }

    /**
     * Поиск рубрик и подрубрик
     *
     * @param \MongoClient $mongo
     * @param              $rubricIds
     *
     * @return array
     */
    protected function _getRubrics(\MongoClient $mongo, $rubricIds)
    {
        $rubricIds = array_map('intval', (array)$rubricIds);
        $criteria = [
            'path' => ['$in' => $rubricIds]
        ];

        $cursor = $mongo->selectCollection('gis', 'rubrics')->find($criteria);

        $rubricIds = array_merge(ArrayHelper::column($cursor, 'id'), $rubricIds);

        return array_values(array_unique($rubricIds));
    }

    /**
     * Подгрузка инфы о рубриках
     *
     * @param \MongoClient $mongo
     * @param              $result
     *
     * @return array
     */
    protected function _loadRubrics(\MongoClient $mongo, $result)
    {
        $rubricIds = [];
        foreach ($result as $row) {
            $rubricIds = array_merge($rubricIds, $row['rubrics']);
        }
        $rubricIds = array_map('intval', array_unique($rubricIds));

        $rubricsData = $mongo->selectCollection('gis', 'rubrics')
            ->find(['id' => ['$in' => $rubricIds]]);

        $rubricsData = ArrayHelper::indexBy($rubricsData, 'id');

        foreach ($result as $key => $row) {
            $rubrics = [];
            foreach ($row['rubrics'] as $rubricId) {
                $rubrics[] = [
                    'id' => $rubricId,
                    'title' => $rubricsData[$rubricId]['title']
                ];
            }
            $result[$key]['rubrics'] = $rubrics;
        }

        return $result;
    }

    /**
     * Подгрузка инфы о зданиях
     *
     * @param \MongoClient $mongo
     * @param              $result
     *
     * @return array
     */
    protected function _loadBuilding(\MongoClient $mongo, $result)
    {
        $buildingIds = [];
        foreach ($result as $row) {
            $buildingIds[] = (int) $row['building_id'];
        }
        $buildingIds = array_unique($buildingIds);

        $buildingsData = $mongo->selectCollection('gis', 'buildings')
            ->find(['id' => ['$in' => $buildingIds]]);
        $buildingsData = ArrayHelper::indexBy($buildingsData, 'id');

        foreach ($result as $key => $row) {
            $result[$key]['building'] = [
                'id' => $row['building_id'],
                'address' => $buildingsData[$row['building_id']]['address'],
            ];
            unset($result[$key]['building_id']);
        }

        return $result;
    }
}
