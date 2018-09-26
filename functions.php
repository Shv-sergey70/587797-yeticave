<?php
//Функция-шаблонизатор
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require($name);

    $result = ob_get_clean();

    return $result;
}
//Создадим функцию module2-task2
function toPriceFormat($num) {
    $number = ceil($num);
    if ($number >= 1000) {
        $number = number_format($number, 0, '.', ' ');
    }
    return $number.' &#8381;';
}
//module3-task2
date_default_timezone_set('Europe/Moscow');
// date_default_timezone_set('GMT');
function getTimeToMidnight() {
	$second_to_midnight = strtotime('tomorrow') - time();
  return gmdate('H:i:s', $second_to_midnight);
}