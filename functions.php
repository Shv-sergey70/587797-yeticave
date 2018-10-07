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
//Функция для определения разницы времени между определенным моментов и настоящим
function getTimeDiff($future_time) {
    $seconds_diff = strtotime($future_time) - time();
    $seconds_to = add0ToDate(floor(($seconds_diff)%60));
    $minutes_to = add0ToDate(floor(($seconds_diff/60)%60));
    $hours_to = add0ToDate(floor($seconds_diff/3600));
    return $hours_to.':'.$minutes_to.':'.$seconds_to;
}
// Функция проверяет на существование результат запроса - если нет - отправляет 404
function checkForExistanceDBres($checking_item)
{
  if (empty($checking_item)) {
    header("HTTP/1.x 404 Not Found");
    die();
  }
}
// Функция проверяет на существование результат запроса - если нет - отправляет 404
function checkDBError($checking_item, $link)
{
  if (!$checking_item) {
      $error = mysqli_error($link);
      print("Ошибка: Невозможно выполнить запрос к БД " . $error);
      die();
    }
}