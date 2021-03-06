<?php

$mongo = new MongoClient("mongodb://localhost:27017");

$streets = [
    'Красный проспект',
    'Олеко Дундича',
    "1 Кирзавод улица", "12 Декабря улица", "2 Кирзавод улица", "20 Партсъезда улица", "25 лет Октября улица", "3 Интернационала улица", "3 Сентября улица", "30 лет Октября улица", "4 Пятилетки улица", "40 лет Комсомола улица", "40 лет Октября улица", "5 Декабря улица", "6 Пятилетки 1-й переулок", "8 Кордон улица", "8 Марта улица", "9 Гвардейской Дивизии улица", "9 Ноября улица", "91 Перекат улица", "Абаканская улица", "Абразивная улица", "Абхазская улица", "Авиастроителей улица", "Авиационная улица", "Автогенная улица", "Автомобильная улица", "Автономная улица", "Агатовая улица", "Агрономическая улица", "Азербайджанская улица", "Азовский переулок", "Азолина переулок", "Айвазовского малая улица", "Айвазовского улица", "Академическая улица", "Акмолинская улица", "Аксенова улица", "Актюбинский переулок", "Албазинская улица", "Алданская улица", "Алейская улица", "Александра Невского улица", "Алексеева переулок", "Алеутская улица", "Алма-Атинская 2-я улица", "Алма-Атинская улица", "Алмазная улица", "Алтайская улица", "Алтайский 1-й переулок", "Алтайский 2-й переулок", "Алтайский переулок", "Алуштинская улица", "Альпийская улица", "Альпийский переулок", "Амбулаторная улица", "Амбулаторный переулок", "Амурская улица", "Амурский 1-й переулок", "Амурский 2-й переулок", "Амурский 3-й переулок", "Ангарная улица", "Андреева улица", "Андриена Лежена улица", "Анжерская улица", "Аникина улица", "Анисовая улица", "Анодная улица", "Аносова улица", "Апрельская улица", "Аральская улица", "Арбатская улица", "Арбузова улица", "Аргунский переулок", "Аренского улица", "Арзамасская улица", "Арктическая улица", "Армавирская улица", "Артиллерийская улица", "Архитектурная 2-я улица", "Архитектурная улица", "Архонский переулок", "Астраханская улица", "Астрономическая улица", "Асфальтный переулок", "Ачинская улица", "Ашхабадская улица", "Аэропорт улица", "Бабушкина улица", "Баганская улица", "Баженова улица", "База Геологии улица",
    "Войкова тупик", "Войкова улица", "Вокзальная магистраль", "Волжская улица", "Волжский переулок", "Волколакова улица", "Володарского улица", "Волочаевская улица", "Волочаевский переулок", "Волховская улица", "Воровского улица", "Воронежская улица", "Воронежский переулок", "Воскова улица", "Восточная 2-я улица", "Восточный проезд", "Восход улица", "Выборная улица", "Выборный переулок", "Выборный проезд", "Высокая улица", "Высоковольтная улица", "Высоковольтный переулок", "Высокогорная 2-я улица", "Высокогорная улица", "Высотная 1-я улица", "Высотная улица", "Высоцкого улица", "Выставочная улица", "Вяземская улица", "Газовая 1-я улица", "Газовая 2-я улица", "Газовый проезд", "Газонная улица", "Гайдара улица", "Галилея улица", "Галковского улица", "Галущака улица", "Гаражная улица", "Гаранина улица", "Гарина-Михайловского площадь", "Гаршина улица", "Гастелло улица", "Гастрономическая улица", "Генераторная улица", "Геодезическая улица", "Геологическая улица", "Геофизическая улица", "Герцена улица", "Гидромонтажная улица", "Гидростроителей улица", "Гипсовая улица", "Гладкова улица", "Глинки улица", "Гнесиных улица", "Гоголя улица", "Годовикова улица", "Гомельская улица", "Гончарная улица", "Гончарный переулок", "Гончарова улица", "Горбаня улица", "Горная улица", "Горный переулок", "Горская улица", "Горский 1-й переулок", "Горский 2-й переулок", "Горский 3-й переулок", "Горский мкр", "Горького улица", "Гостиная улица", "Гравийный переулок", "Гражданская 2-я улица", "Гражданская улица", "Гранитная улица", "Граничный переулок", "Грекова 1-й переулок", "Грекова 2-й переулок", "Грекова тупик", "Грекова улица", "Грибоедова улица", "Григоровича улица", "Гризодубовой улица", "Громова улица", "Грузинская 1-я улица", "Грузинская улица", "Грузинский 1-й переулок", "Грузинский 2-й переулок", "Грунтовая улица", "Грушевская 1-я улица",
    "Ипподромская улица", "Иркутская улица", "Иртышская улица", "Искитимская 1-я улица", "Искитимская 2-я улица", "Искры улица", "Истринская улица", "Кабардинская улица", "Кавалерийская малая улица", "Кавалерийская улица", "Кавалерийский переулок", "Кавалерийского Полка улица", "Кавалькадная улица", "Кавказская улица", "Кавказский 1-й переулок", "Кавказский 2-й переулок", "Кавказский переулок", "Казарменная улица", "Казачинская улица", "Каинская улица", "Кайтымовская 1-я улица", "Кайтымовская улица", "Калибровая улица", "Калинина улица", "Калиновая улица", "Калужская улица", "Калужский 1-й переулок", "Калужский 2-й переулок", "Калужский 3-й переулок", "Калужский 4-й переулок", "Калужский 5-й переулок", "Калужский 6-й переулок", "Калужский 7-й переулок", "Калужский 8-й переулок", "Калужский 9-й переулок", "Каменка Левый берег улица", "Каменка Правый берег улица", "Каменогорская улица", "Каменогорский 1-й переулок", "Каменогорский 2-й переулок", "Каменская улица", "Каменский Тракт улица", "Камчатская улица", "Камышенская улица", "Камышенский 1-й переулок", "Камышенский 10-й переулок", "Камышенский 2-й переулок", "Камышенский 3-й переулок", "Камышенский 4-й переулок", "Камышенский 5-й переулок", "Камышенский 6-й переулок", "Камышенский 7-й переулок", "Камышенский 8-й переулок", "Камышенский 9-й переулок", "Камышенский Лог переулок", "Капитанская улица", "Караваева улица", "Карамзина улица", "Карбышева улица", "Карельская улица", "Карла Либкнехта улица", "Карла Маркса площадь", "Карла Маркса проспект", "Карпатская улица", "Карпинского 2-я улица", "Карпинского улица", "Карская улица", "Карьерный Лог переулок", "Каспийская улица", "Катодная улица", "Катунская улица", "Каунасская улица", "Каховская улица", "Качалова улица", "Каширская улица", "Каштановая 1-я улица", "Каштановая 2-я улица", "Каштановая улица", "Кедровая улица", "Кемеровская улица", "Керченская улица", "Киевская улица", "Кирзаводская 1-я улица", "Кирзаводская 2-я улица", "Кирзаводская 9-я улица", "Кирзаводская улица", "Кирзаводский 1-й переулок", "Кирзаводский 2-й переулок", "Кирова площадь", "Кирова улица",

];

function apiGeoRequest($address)
{
    $url = 'https://geocode-maps.yandex.ru/1.x/?format=json&geocode=' . $address;
    return file_get_contents($url);
}

function getGeoResponse($address)
{
    $response = json_decode(apiGeoRequest($address), true);

    if (empty($response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
        return null;
    }

    $geoObject = $response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'];
    $addressLine = $geoObject['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AddressLine'];

    $coords = $geoObject['Point']['pos'];
    $coords = explode(' ', $coords);

    return [
        'coords' => [(float)$coords[1], (float)$coords[0]],
        'address' => $addressLine
    ];
}

$cursor = $mongo->selectCollection('gis', 'buildings')->find()->sort(['id' => -1])->limit(1);
$buildings = iterator_to_array($cursor);

if (!empty($buildings)) {
    $docId = reset($buildings)['id'] + 1;
} else {
    $docId = 1;
}

foreach ($streets as $street) {
    for ($i = 0; $i < 50; $i++) {
        $response = getGeoResponse('Новосибирск, ' . $street . ', ' . $i);
        if (!$response) {
            break;
        }

        $mongo->selectCollection('gis', 'buildings')->insert(
            [
                'id' => $docId,
                'address' => $response['address'],
                'coords' => $response['coords']
            ]
        );
        echo 'document number: ' . $docId . PHP_EOL;
        $docId++;
    }
}