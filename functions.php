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
  if ($num === NULL) {
    return;
  }
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
// Функция отправляет запрос в БД и возвращает многомерный ассоциативный массив
function get_DB_query_rows($query, $link) {
    $query_result = mysqli_query($link, $query);
    if (!$query_result) {
      $error = mysqli_error($link);
      print("Ошибка в запросе $query: $error");
      die();
    }
    $fetched_query_result = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    return $fetched_query_result;
}
// Функция отправляет запрос в БД и возвращает ассоциативный массив одного значения
function get_DB_query_row($query, $link) {
    $query_result = mysqli_query($link, $query);
     if (!$query_result) {
      $error = mysqli_error($link);
      print("Ошибка в запросе $query: $error");
      die();
    }
    $fetched_query_result = mysqli_fetch_assoc($query_result);
    return $fetched_query_result;
}
// Функция отправляет запрос в БД и вставляет 1 значение в БД, возвращает ID вставленной записи
function put_DB_query_row($query, $link) {
    $query_result = mysqli_query($link, $query);
     if (!$query_result) {
      $error = mysqli_error($link);
      print("Ошибка в запросе $query: $error");
      die();
    }
    return mysqli_insert_id($link);
}