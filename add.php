<?php
declare(strict_types=1);
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;
//Ограничен доступ не анонимных пользователей
isAuth($USER);
//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_res($menu_items_query, $link, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$lot = $_POST;

	$required = ['NAME', 'CATEGORY', 'DESCRIPTION', 'START_PRICE', 'PRICE_STEP', 'FINISH_DATE'];
	$dict = ['NAME' => 'Наименование', 'CATEGORY' => 'Категория', 'DESCRIPTION' => 'Описание', 'IMAGE_URL' => 'Изображение', 'START_PRICE' => 'Начальная цена', 'PRICE_STEP' => 'Шаг ставки', 'FINISH_DATE' => 'Дата окончания торгов'];
	$errors = [];
	foreach ($required as $value) {
		if (empty($lot[$value])) {
			$errors[$value] = 'Это поле надо заполнить';
		}
	}
	if (!empty($lot['START_PRICE']) && (!ctype_digit($lot['START_PRICE']) || intval($lot['START_PRICE']) <= 0)) {
		$errors['START_PRICE'] = 'Это поле должно быть целым числом больше нуля';
	}
	if (!empty($lot['PRICE_STEP']) && (!ctype_digit($lot['PRICE_STEP']) || intval($lot['PRICE_STEP']) <= 0)) {
		$errors['PRICE_STEP'] = 'Это поле должно быть целым числом больше нуля';
	}
	if (!empty($lot['FINISH_DATE'])) {
		$unixtime = strtotime($lot['FINISH_DATE']);
		if ($unixtime) {
			if ($unixtime >= strtotime('tomorrow')) {
				$date_for_insert = date('Y-m-d', $unixtime);
			} else {
				$errors['FINISH_DATE'] = 'Указанная дата должна быть больше текущей даты хотя бы на один день';
			}
		} else {
			$errors['FINISH_DATE'] = 'Введите дату в формате ДД.ММ.ГГГГ';
		}
	}
	//Проверка существования выбранной категории
	if (!empty($lot['CATEGORY'])) {
		$safe_CATEGORY_ID = intval($lot['CATEGORY']);
		$category_check_query = "SELECT id AS ID
														 FROM categories
														 WHERE id = ".$safe_CATEGORY_ID;
		if (!mysqli_num_rows(mysqli_query($link, $category_check_query))) {
			$errors['CATEGORY'] = 'Выберите категорию из списка';
		}
	}

	//Проверка изображения
  $file_arr = checkUserImageFromForm($_FILES, 'IMAGE_URL', true);
  if ($file_arr['ERROR']) {
  	$errors['IMAGE_URL'] = $file_arr['ERROR'];
  }

	if (count($errors)) {
		$page_content = include_template('add.php', 
	  [
	    'menu_items' => $menu_items,
	    'errors' => $errors,
	    'dict' => $dict,
	    'lot' => $lot
	  ]);
	} else {
		move_uploaded_file($file_arr['TMP_NAME'], 'img/'.$file_arr['NEW_NAME']); //Перемещаем файл, загруженный юзером
		//Запрос на добавление нового лота
		$safe_NAME = mysqli_real_escape_string($link, $lot['NAME']);
		$safe_DESCRIPTION = mysqli_real_escape_string($link, $lot['DESCRIPTION']);
		$safe_IMAGE_URL = mysqli_real_escape_string($link, $file_arr['URL']);
		$safe_START_PRICE = intval($lot['START_PRICE']);
		$safe_FINISH_DATE = mysqli_real_escape_string($link, $date_for_insert);
		$safe_PRICE_STEP = intval($lot['PRICE_STEP']);
		$user_id = $_SESSION['USER']['id'];

		$lot_add_query = "INSERT INTO lots
											SET
											date_create = NOW(),
											name = '$safe_NAME',
											description = '$safe_DESCRIPTION',
											image_url = '$safe_IMAGE_URL',
											start_price = '$safe_START_PRICE',
											date_end = '$safe_FINISH_DATE',
											bet_step = '$safe_PRICE_STEP',
											author_id = '$user_id',
											adv_category_id = ".$safe_CATEGORY_ID;
		$inserted_lot_id = put_DB_query_row($lot_add_query, $link);
		header('Location: lot.php?ID='.$inserted_lot_id);
		die();
	}
} else {
	$page_content = include_template('add.php', 
  [
    'menu_items' => $menu_items
  ]);
}


$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items, 
    'search_query' => $_GET['search']??'',
    'title' => 'Yeticave', 
    'USER'=> $USER
  ]);
print($layout_content);