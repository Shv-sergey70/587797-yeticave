<?php
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
$user = require_once('user.php');

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_rows($menu_items_query, $link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
	$login = $_POST;
	$required = ['EMAIL', 'PASSWORD'];
	$dict = ['EMAIL' => 'E-mail', 'PASSWORD' => 'Пароль'];
	$errors = [];
	foreach ($required as $value) {
		if (empty($login[$value])) {
			$errors[$value] = 'Это поле надо заполнить';
		}
	}
	if (!empty($login['EMAIL']) && !filter_var($login['EMAIL'], FILTER_VALIDATE_EMAIL)) {
		$errors['EMAIL'] = 'Введите валидный E-mail адрес';
	} else if (!empty($login['EMAIL'])) {
		//Запрос на получение пользователя по введенному EMAIL
		$safe_EMAIL = mysqli_real_escape_string($link, $login['EMAIL']);
		$email_query = "SELECT * FROM users WHERE email = '$safe_EMAIL'";
		$user_from_db = get_DB_query_row($email_query, $link);
		if (!$user_from_db) {
			$errors['EMAIL'] = 'Пользователь с таким EMAIL не зарегистрирован';
		}
	}
	//Запрос на сравнение паролей
	if (!empty($login['PASSWORD'])) {
		$safe_PASSWORD = mysqli_real_escape_string($link, $login['PASSWORD']);
		if (!password_verify($safe_PASSWORD, $user_from_db['password'])) {
			$errors['PASSWORD'] = 'Неверно введен пароль';
		} 
	}

	if (count($errors)) {
		$page_content = include_template('login.php', 
	  [
	    'menu_items' => $menu_items,
	    'errors' => $errors,
	    'dict' => $dict,
	    'login' => $login
	  ]);
	} else {
		session_start();
		$_SESSION['USER'] = $user_from_db;
		header('Location: '.MAIN_DIR);
	}
} else {
	$page_content = include_template('login.php', 
  [
    'menu_items' => $menu_items
  ]);
}

$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items, 
    'title' => 'Yeticave'
  ]);
print($layout_content);