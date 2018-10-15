<?php 
declare(strict_types=1);
date_default_timezone_set('Europe/Moscow');
/**
  * Функция-шаблонизатор
  * @param string $name  Имя сценария-шаблона
  * @param array $data  Массив с переменными, которые будут доступны в подключаемом шаблоне
  *
  * @return string Отрисованная страница из шаблона
  */
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
/**
  * Добавляет разрядность и знак рубля к числу
  * @param int $num  Целое число для форматирования
  *
  * @return string Форматированное число со знаком рубля
  */
function toPriceFormat(int $num): string {
  $number = ceil($num);
  if ($number >= 1000) {
      $number = number_format($number, 0, '.', ' ');
  }
  return $number.' &#8381;';
}
/**
  * Добавляет к числу 0 (ведущий ноль), если секунд, минут или часов меньше 10
  * @param string $value  Число в виде строки для добавления ведущего нуля
  *
  * @return string Число в виде строки с добавленным ведущим нулем
  */
function add0ToDate(string $value): string {
	if ((int)$value < 10) {
		$value = '0'.$value;
	}
	return $value;
}
/**
  * Определяет разницу времени между определенным моментом и настоящим
  * @param string $future_time  Определенная дата
  *
  * @return string Дата в формате H:i:s
  */
function getTimeDiff(string $future_time): string {
    $seconds_diff = strtotime($future_time) - time();
    $seconds_to = add0ToDate((string)floor(($seconds_diff)%60));
    $minutes_to = add0ToDate((string)floor(($seconds_diff/60)%60));
    $hours_to = add0ToDate((string)floor($seconds_diff/3600));
    return $hours_to.':'.$minutes_to.':'.$seconds_to;
}
/**
  * Проверяет на существование результат запроса - если нет - отправляет 404
  * @param array|NULL $checking_item  Результат запроса к БД
  *
  * @return void
  */
function checkForExistanceDBres(?array $checking_item): void {
  if (empty($checking_item)) {
    header("HTTP/1.x 404 Not Found");
    die();
  }
}
/**
  * Отправляет запрос в БД и возвращает многомерный или одномерный ассоциативный массив
  * @param string $query  Запрос для БД
  * @param mysqli $link Ресурс соединения с БД
  * @param bool $isMulti Возвращать многомерный(true) или одномерный(false) ассоциативный массив
  *
  * @return array Результат запроса к БД в виде ассоциативного массива
  */
function get_DB_query_res(string $query, mysqli $link, bool $isMulti = true): ?array {
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
/**
  * Отправляет запрос в БД и вставляет 1 значение в БД
  * @param string $query  Запрос для БД
  * @param mysqli $link Ресурс соединения с БД
  *
  * @return int ID вставленной записи
  */
function put_DB_query_row(string $query, mysqli $link): int {
    $query_result = mysqli_query($link, $query);
     if (!$query_result) {
      $error = mysqli_error($link);
      print("Ошибка в запросе $query: $error");
      die();
    }
    return mysqli_insert_id($link);
}
/**
  * Проверяет картинку, загруженную юзером, на соответствие типу (png, jpeg, jpg)
  * @param array $file  Массив с картинкой из глобальной переменной _FILES
  * @param string $input_name Название поля загружаемой картинку
  * @param bool $isRequired Является ли картинка обязательным полем
  *
  * @return array массив с ошибками и данными о картинке
  */
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
/**
  * Приводит дату к человекопонятному виду с правильным склоенением
  * @param int $time Дата в unix-формате
  *
  * @return string Строка с датой в человекопонятном виде
  */
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
/**
  * Приводит дату к человекопонятному виду
  * @param int $time Дата в unix-формате
  * @param string $type Определенная единица измерения (дни, месяцы...)
  *
  * @return string Дата с правильным склонением
  */
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
/**
  * Склоняет слова
  * @param int $number Количество элементов для склоенения
  * @param array $after Фразы в разных вариациях для склонения
  *
  * @return string Склоененное число со словом
  */
function plural_form(int $number, array $after): string {
  $cases = array (2, 0, 1, 1, 1, 2);
  return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}
/**
  * Создает пагинацию
  * @param int $cur_page Текущая страница
  * @param int $elements_count Общее количество элементов для пагинации
  * @param int $element_per_page Количество элементов на одну страницу
  *
  * @return array Массив с ключами (предыдущей страницы, следующей страницы, смещения пагинации, количество страниц, страницы для пагинации, текущей страницы, количества элементов на страницу)
  */
function createPagination(int $cur_page, int $elements_count, int $element_per_page): array {
  $pages_count = (int)ceil($elements_count/$element_per_page); //Считаем количество страниц
  $offset = ($cur_page - 1) * $element_per_page; //Смещение для запроса к БД
  $pages = range(1, $pages_count); //Массив страниц для пагинации
  if ($cur_page > 1) {
    $prev_page = $cur_page - 1;
  } else {
    $prev_page = $cur_page;
  }
  if ($cur_page < $pages_count) {
    $next_page = $cur_page + 1;
  } else {
    $next_page = $cur_page;
  }
  return ['PREV_PAGE' => $prev_page, 'NEXT_PAGE' => $next_page, 'OFFSET' => $offset, 'PAGES_COUNT' => $pages_count, 'PAGES' => $pages, 'CURRENT_PAGE' => $cur_page, 'ELEMENT_PER_PAGE' => $element_per_page];
}
/**
  * Проверяет аутентифицирован ли пользователь
  * @param array $user Массив с данными о пользователе из сессии
  *
  * @return void - die и 403
  */
function isAuth(?array $user) {
  if (!$user) {
    header('HTTP/1.x 403');
    die();
  }
}