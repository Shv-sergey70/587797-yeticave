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
//Функция проверяет картинку, загруженную юзером, на соответствие типу
function checkUserImageFromForm($file, &$item, &$errors_arr, $isRequired = true) {
  if (!empty($file['name'])) {
    $tmp_name = $file['tmp_name'];
    $mime_extension_map = [
      'image/png' => 'png',
      'image/jpeg' => 'jpeg',
      'image/jpg' => 'jpg'
    ];
    $file_type = mime_content_type($tmp_name);
    if (isset($mime_extension_map[$file_type])) {
      $file_extension = $mime_extension_map[$file_type];
      $new_name = uniqid('img_').'.'.$file_extension;
      $item['IMAGE_URL'] = 'img/'.$new_name;
      return ['TMP_NAME' => $tmp_name, 'NEW_NAME' => $new_name];
    } else {
      $errors_arr['IMAGE_URL'] = 'Загрузите картинку в формате jpg, jpeg или png';
    }
  } elseif ($isRequired) {
    $errors_arr['IMAGE_URL'] = 'Вы не загрузили картинку';
  } else {
    $item['IMAGE_URL'] = '';
  }
}

//Функция приводит дату к человекопонятному виду
function showDate($time) { // Определяем количество и тип единицы измерения
  $time = time() - $time;
  if ($time < 60) {
    return 'меньше минуты назад';
  } elseif ($time < 3600) {
    return dimension((int)($time/60), 'i');
  } elseif ($time < 86400) {
    return dimension((int)($time/3600), 'G');
  } elseif ($time < 2592000) {
    return dimension((int)($time/86400), 'j');
  } elseif ($time < 31104000) {
    return dimension((int)($time/2592000), 'n');
  } elseif ($time >= 31104000) {
    return dimension((int)($time/31104000), 'Y');
  }
}
function dimension($time, $type) { // Определяем склонение единицы измерения
  $dimension = [
    'n' => ['месяцев', 'месяц', 'месяца', 'месяц'],
    'j' => ['дней', 'день', 'дня'],
    'G' => ['часов', 'час', 'часа'],
    'i' => ['минут', 'минуту', 'минуты'],
    'Y' => ['лет', 'год', 'года']
  ];
    if ($time >= 5 && $time <= 20)
        $n = 0;
    else if ($time == 1 || $time % 10 == 1)
        $n = 1;
    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
        $n = 2;
    else
        $n = 0;
    return $time.' '.$dimension[$type][$n]. ' назад';
}