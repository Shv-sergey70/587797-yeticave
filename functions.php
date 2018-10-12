<?php 
declare(strict_types=1);
date_default_timezone_set('Europe/Moscow');
//Функция-шаблонизатор
function include_template(string $name, array $data): string {
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
function toPriceFormat(int $num): string {
  $number = ceil($num);
  if ($number >= 1000) {
      $number = number_format($number, 0, '.', ' ');
  }
  return $number.' &#8381;';
}
//Функция для определения времени до полуночи - module3-task2
function getTimeToMidnight(): string {
	$second_to_midnight = strtotime('tomorrow') - time();
    $minutes_to_midnight = add0ToDate((string)floor(($second_to_midnight/60)%60));
    $hours_to_midnight = add0ToDate((string)floor($second_to_midnight/3600));
	return $hours_to_midnight.':'.$minutes_to_midnight;
}
//Функция добавляет 0, если минут или часов меньше 10
function add0ToDate(string $value): string {
	if ((int)$value < 10) {
		$value = '0'.$value;
	}
	return $value;
}
//Функция для определения разницы времени между определенным моментов и настоящим
function getTimeDiff(string $future_time): string {
    $seconds_diff = strtotime($future_time) - time();
    $seconds_to = add0ToDate((string)floor(($seconds_diff)%60));
    $minutes_to = add0ToDate((string)floor(($seconds_diff/60)%60));
    $hours_to = add0ToDate((string)floor($seconds_diff/3600));
    return $hours_to.':'.$minutes_to.':'.$seconds_to;
}
// Функция проверяет на существование результат запроса - если нет - отправляет 404
function checkForExistanceDBres(?array $checking_item) {
  if (empty($checking_item)) {
    header("HTTP/1.x 404 Not Found");
    die();
  }
}
// Функция отправляет запрос в БД и возвращает многомерный или одномерный ассоциативный массив
function get_DB_query_res(string $query, $link, bool $isMulti = true): ?array {
  $query_result = mysqli_query($link, $query);
  if (!$query_result) {
    $error = mysqli_error($link);
    print("Ошибка в запросе $query: $error");
    die();
  }
  if ($isMulti) {
    $fetched_query_result = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    return $fetched_query_result;
  } else {
    $fetched_query_result = mysqli_fetch_assoc($query_result);
    return $fetched_query_result;
  }   
}
// Функция отправляет запрос в БД и вставляет 1 значение в БД, возвращает ID вставленной записи
function put_DB_query_row(string $query, $link): int {
    $query_result = mysqli_query($link, $query);
     if (!$query_result) {
      $error = mysqli_error($link);
      print("Ошибка в запросе $query: $error");
      die();
    }
    return mysqli_insert_id($link);
}
//Функция проверяет картинку, загруженную юзером, на соответствие типу
function checkUserImageFromForm(array $file, string $input_name, bool $isRequired = true): array {
  if (!empty($file[$input_name]['name'])) {
    $tmp_name = $file[$input_name]['tmp_name'];
    $mime_extension_map = [
      'image/png' => 'png',
      'image/jpeg' => 'jpeg',
      'image/jpg' => 'jpg'
    ];
    $file_type = mime_content_type($tmp_name);
    if (isset($mime_extension_map[$file_type])) {
      $file_extension = $mime_extension_map[$file_type];
      $new_name = uniqid('img_').'.'.$file_extension;
      return ['URL' => 'img/'.$new_name, 'TMP_NAME' => $tmp_name, 'NEW_NAME' => $new_name, 'ERROR' => NULL];
    } else {
      return ['ERROR' => 'Загрузите картинку в формате jpg, jpeg или png'];
    }
  } elseif ($isRequired) {
    return ['ERROR' => 'Вы не загрузили картинку'];
  } else {
    return ['URL' => '', 'ERROR' => NULL];
  }
}

//Функция приводит дату к человекопонятному виду
function showDate(int $time): string { // Определяем количество и тип единицы измерения
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
function dimension(int $time, string $type): string { // Определяем склонение единицы измерения
  $dimension = [
    'n' => ['месяцев', 'месяц', 'месяца', 'месяц'],
    'j' => ['дней', 'день', 'дня'],
    'G' => ['часов', 'час', 'часа'],
    'i' => ['минут', 'минуту', 'минуты'],
    'Y' => ['лет', 'год', 'года']
  ];
    if ($time >= 5 && $time <= 20)
        $n = 0;
    else if ($time === 1 || $time % 10 === 1)
        $n = 1;
    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
        $n = 2;
    else
        $n = 0;
    return $time.' '.$dimension[$type][$n]. ' назад';
}