<?php
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
$user = require_once('user.php');

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_rows($menu_items_query, $link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
	$account = $_POST;
	$required = ['EMAIL', 'PASSWORD', 'NAME', 'MESSAGE'];
	$dict = ['EMAIL' => 'E-mail', 'PASSWORD' => 'Пароль', 'NAME' => 'Имя', 'MESSAGE' => 'Контактные данные', 'AVATAR' => 'Аватар'];
	$errors = [];
	foreach ($required as $value) {
		if (empty($account[$value])) {
			$errors[$value] = 'Это поле надо заполнить';
		}
	}
	if (!empty($account['EMAIL']) && !filter_var($account['EMAIL'], FILTER_VALIDATE_EMAIL)) {
		$errors['EMAIL'] = 'Введите валидный E-mail адрес';
	}
	if (!empty($account['EMAIL'])) {
		$safe_EMAIL = mysqli_real_escape_string($link, $account['EMAIL']);
		$email_query = 'SELECT COUNT(*) AS EMAILS_COUNT FROM users WHERE email = "'.$safe_EMAIL.'"';
		if (get_DB_query_row($email_query, $link)['EMAILS_COUNT']) {
				$errors['EMAIL'] = 'Такой E-mail адрес уже зарегистрирован на сайте';
		}
	}
	if (!empty($_FILES['AVATAR']['name'])) {
		$tmp_name = $_FILES['AVATAR']['tmp_name'];
		$original_name = $_FILES['AVATAR']['name'];
		$file_type = mime_content_type($tmp_name);
		$new_name = uniqid('img_').'.'.pathinfo($_FILES['AVATAR']['name'], PATHINFO_EXTENSION);

		if ($file_type === 'image/png' || $file_type === 'image/jpeg') {
			move_uploaded_file($tmp_name, 'img/'.$new_name);
			$account['AVATAR'] = 'img/'.$new_name;
		} else {
			$errors['AVATAR'] = 'Загрузите картинку в формате jpg, jpeg или png';
		}
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
		//Запрос на добавление нового пользователя
		$safe_NAME = mysqli_real_escape_string($link, $account['NAME']);
		$safe_PASSWORD = mysqli_real_escape_string($link, $account['PASSWORD']);
		$safe_PASSWORD_HASH = password_hash($safe_PASSWORD, PASSWORD_DEFAULT);
		$safe_MESSAGE = mysqli_real_escape_string($link, $account['MESSAGE']);

		$user_add_query = "INSERT INTO users
											SET
											date_register = NOW(),
											email = '".$safe_EMAIL."',
											name = '".$safe_NAME."',
											password = '".$safe_PASSWORD_HASH."',
											avatar_url = '".$safe_START_PRICE."',
											contacts = ".$safe_MESSAGE;
		$inserted_user_id = put_DB_query_row($user_add_query, $link);
		header('Location: login.php');
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
    'title' => 'Yeticave', 
    'user'=>$user
  ]);
print($layout_content);