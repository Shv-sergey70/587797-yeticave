<?php
declare(strict_types=1);
require_once('functions.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_res($menu_items_query, $link, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
	$account = $_POST;
	$required = ['EMAIL', 'PASSWORD', 'NAME', 'MESSAGE'];
	$dict = ['EMAIL' => 'E-mail', 'PASSWORD' => 'Пароль', 'NAME' => 'Имя', 'MESSAGE' => 'Контактные данные', 'IMAGE_URL' => 'Аватар'];
	$errors = [];
	foreach ($required as $value) {
		if (empty($account[$value])) {
			$errors[$value] = 'Это поле надо заполнить';
		}
	}
	//Проверка валидности и занятости EMAILа
	if (!empty($account['EMAIL']) && !filter_var($account['EMAIL'], FILTER_VALIDATE_EMAIL)) {
		$errors['EMAIL'] = 'Введите валидный E-mail адрес';
	} elseif (!empty($account['EMAIL'])) {
		$safe_EMAIL = mysqli_real_escape_string($link, $account['EMAIL']);
		$email_query = 'SELECT email AS EMAIL FROM users WHERE email = "'.$safe_EMAIL.'"';
		if (mysqli_num_rows(mysqli_query($link, $email_query))) {
			$errors['EMAIL'] = 'Такой E-mail адрес уже зарегистрирован на сайте';
		}
	}
	
	//Проверка изображения
	$file_arr = checkUserImageFromForm($_FILES, 'IMAGE_URL', false);
	if ($file_arr['ERROR']) {
  	$errors['IMAGE_URL'] = $file_arr['ERROR'];
  }

	if (count($errors)) {
		$page_content = include_template('register.php', 
	  [
	    'menu_items' => $menu_items,
	    'errors' => $errors,
	    'dict' => $dict,
	    'account' => $account
	  ]);
	} else {
		if (!empty($file_arr['URL'])) {
			move_uploaded_file($file_arr['TMP_NAME'], 'img/'.$file_arr['NEW_NAME']);//Перемещаем картинку, загруженную юзером
		}
		//Запрос на добавление нового пользователя
		$safe_NAME = mysqli_real_escape_string($link, $account['NAME']);
		$PASSWORD_HASH = mysqli_real_escape_string($link, password_hash($account['PASSWORD'], PASSWORD_DEFAULT));
		$safe_MESSAGE = mysqli_real_escape_string($link, $account['MESSAGE']);
		$safe_AVATAR = $file_arr['URL']; //тк мы его генерируем сами, не подвергаем экранированию

		$user_add_query = "INSERT INTO users
											SET
											date_register = NOW(),
											email = '$safe_EMAIL',
											name = '$safe_NAME',
											password = '$PASSWORD_HASH',
											avatar_url = '$safe_AVATAR',
											contacts = '$safe_MESSAGE'";
		$inserted_user_id = put_DB_query_row($user_add_query, $link);
		header('Location: login.php');
		die();
	}
} else {
	$page_content = include_template('register.php', 
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