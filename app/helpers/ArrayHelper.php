<?php

namespace app\helpers;

/**
 * Class ArrayHelper
 * @package app\helpers
 * @author Denis Dyadyun <sysadm85@gmail.com>
 */
class ArrayHelper
{
    /**
     * Проиндексировать массив по переданному ключу
     *
     * @param array $arr
     * @param string $key
     *
     * @return array
     */
    public static function indexBy($arr, $key)
    {
        $result = [];
        foreach ($arr as $data) {
            if (array_key_exists($key, $data)) {
                $result[$data[$key]] = $data;
            }
        }

        return $result;
    }

    /**
     * Проверка существования переменной
     *
     * @param      $data
     * @param null $default
     *
     * @return null
     */
    public static function is(&$data, $default = null)
    {
        return isset($data) ? $data : $default;
    }

    /**
     * Возвращает столбец из коллекции
     *
     * @param array $arr
     * @param string $column
     *
     * @return array
     */
    public static function column($arr, $column)
    {
        $result = [];
        foreach ($arr as $row) {
            if (isset($row[$column])) {
                $result[] = $row[$column];
            }
        }

        return $result;
    }
}
