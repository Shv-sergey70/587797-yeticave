<?php
declare(strict_types=1);
require_once('functions.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;
if ($USER) {
	header('Location: /');
	die();
}

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_res($menu_items_query, $link, true);

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
	} elseif (!empty($login['EMAIL'])) {
		//Запрос на получение пользователя по введенному EMAIL
		$safe_EMAIL = mysqli_real_escape_string($link, $login['EMAIL']);
		$email_query = "SELECT * FROM users WHERE email = '$safe_EMAIL'";
		$user_from_db = get_DB_query_res($email_query, $link, false);
	}
	if (!isset($user_from_db)) {
		$errors['WRONG'] = 'Вы ввели неверный email/пароль';
	} elseif (!empty($login['PASSWORD'])) {
		//Запрос на сравнение паролей
		if (!password_verify($login['PASSWORD'], $user_from_db['password'])) {
			$errors['WRONG'] = 'Вы ввели неверный email/пароль';
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
		$_SESSION['USER'] = $user_from_db;
		header('Location: /');
		die();
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
    'search_query' => $_GET['search']??'',
    'title' => 'Yeticave',
    'USER'=> NULL
  ]);
print($layout_content);