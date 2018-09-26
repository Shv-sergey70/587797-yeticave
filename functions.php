<?php
date_default_timezone_set('Europe/Moscow');
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
//Функция для определения времени до полуночи - module3-task2
function getTimeToMidnight() {
	$second_to_midnight = strtotime('tomorrow') - time();
    $minutes_to_midnight = add0ToDate(floor(($second_to_midnight/60)%60));
    $hours_to_midnight = add0ToDate(floor($second_to_midnight/3600));
	return $hours_to_midnight.':'.$minutes_to_midnight;
}
//Функция добавляет 0, если минут или часов меньше 10
function add0ToDate($value) {
	if ($value < 10) {
		$value = '0'.$value;
	}
	return $value;
}