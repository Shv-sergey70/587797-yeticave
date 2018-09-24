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
date_default_timezone_set('UTC');
function getTimeToMidnight() {
	$second_to_midnight = strtotime('tomorrow') - time();
	var_dump(date('H:i:s', $second_to_midnight));
}
getTimeToMidnight();
// $second_to_midnight = strtotime('tomorrow') - time();
// echo "<pre>";
	// var_dump(strtotime('tomorrow'));
	// var_dump(time());
  // var_dump(strtotime('tomorrow') - time());
  // var_dump(date('l jS \of F Y H:i:s A', time()));
  // var_dump(date('l jS \of F Y H:i:s A', strtotime('tomorrow')));
  // var_dump(date('H:i:s', strtotime('tomorrow') - time()));
// echo "</pre>";