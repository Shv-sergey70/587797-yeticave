<?php
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
session_start();

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
	//Проверка валидности и занятости EMAILа
	if (!empty($account['EMAIL']) && !filter_var($account['EMAIL'], FILTER_VALIDATE_EMAIL)) {
		$errors['EMAIL'] = 'Введите валидный E-mail адрес';
	} else if (!empty($account['EMAIL'])) {
		$safe_EMAIL = mysqli_real_escape_string($link, $account['EMAIL']);
		$email_query = 'SELECT email AS EMAIL FROM users WHERE email = "'.$safe_EMAIL.'"';
		if (mysqli_num_rows(mysqli_query($link, $email_query))) {
			$errors['EMAIL'] = 'Такой E-mail адрес уже зарегистрирован на сайте';
		}
	}
	
	if (!empty($_FILES['AVATAR']['name'])) {
		$tmp_name = $_FILES['AVATAR']['tmp_name'];
		$original_name = $_FILES['AVATAR']['name'];
		$mime_extension_map = [
			'image/png' => 'png',
			'image/jpeg' => 'jpeg',
			'image/jpg' => 'jpg'
		];
		$file_type = mime_content_type($tmp_name);
		if (isset($mime_extension_map[$file_type])) {
			$file_extension = $mime_extension_map[$file_type];
			$new_name = uniqid('img_').'.'.$file_extension;
			move_uploaded_file($tmp_name, 'img/'.$new_name);
			$account['AVATAR'] = 'img/'.$new_name;
		} else {
			$errors['AVATAR'] = 'Загрузите картинку в формате jpg, jpeg или png';
		}
	} else {
		$account['AVATAR'] = '';
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
		$PASSWORD_HASH = password_hash($account['PASSWORD'], PASSWORD_DEFAULT);
		$safe_MESSAGE = mysqli_real_escape_string($link, $account['MESSAGE']);
		$safe_AVATAR = $account['AVATAR']; //тк мы его генерируем сами, не подвергаем экранированию

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
    'title' => 'Yeticave', 
    'USER'=>$_SESSION['USER']
  ]);
print($layout_content);