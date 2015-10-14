<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 14.10.15
 * Time: 22:01
 */

$mongo = new MongoClient("mongodb://localhost:27017");

$firmCollection = $mongo->selectCollection('gis', 'firms');

function generateFirmName($letterCount = 5)
{
    $prefixes = ['ЗАО', 'OOO', 'ИП', 'ОАО'];
    $alpha = 'абвгдеёжзиклмнопрстуфхцчшщыэюя';

    $name = '';
    for ($i = 0; $i < $letterCount; $i++) {
        $name .= mb_substr($alpha, rand(0, mb_strlen($alpha, 'utf8') - 1), 1, 'utf8');
    }

    return $prefixes[array_rand($prefixes)] . ' ' . $name;
}

function generatePhone()
{
    $number = '';

    for ($i = 0; $i < 10; $i++) {
        $number .= rand(0, 9);
    }

    return '+7' . $number;
}

$firm = $firmCollection->find()->sort(['id' => -1])->limit(1)->current();

if (!empty($firm)) {
    $docId = $firm['id'] + 1;
} else {
    $docId = 1;
}

$cursor = $mongo->selectCollection('gis', 'buildings')->find();
foreach ($cursor as $row) {
    $firmCollection->save(
        [
            'id' => $docId,
            'building_id' => $row['id'],
            'coords' => $row['coords'],
            'title' => generateFirmName(),
            'rubrics' => [rand(1, 9)],
            'phones' => [
                generatePhone()
            ]
        ]
    );
    $docId++;
    echo 'insert docId:' . $docId . PHP_EOL;
}