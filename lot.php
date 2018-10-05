<?php
require_once('functions.php');


if (!isset($_GET['ID'])) {
	header("HTTP/1.x 404 Not Found");
  die();
}
$lot_id = intval($_GET['ID']);


$link = mysqli_connect('localhost', 'root', 'root', 'yeticave');
if ($link === false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, 'utf8');

$menu_items_query = 'SELECT * FROM categories';
$menu_items_DB = mysqli_query($link, $menu_items_query);
if (!$menu_items_DB) {
  $error = mysqli_error($link);
  print("Ошибка: Невозможно выполнить запрос к БД " . $error);
  die(); 
}
$menu_items = mysqli_fetch_all($menu_items_DB, MYSQLI_ASSOC);

$lot_query = 'SELECT 
							lots.id as ID,
							lots.name as NAME,
							lots.description as DESCRIPTION,
							lots.image_url as IMAGE_URL,
							categories.name as CATEGORY_NAME
							FROM lots 
							JOIN categories
							ON lots.adv_category_id = categories.id
							WHERE lots.id = '.$lot_id;
$lot_item_DB = mysqli_query($link, $lot_query);
if (!$lot_item_DB) {
  $error = mysqli_error($link);
  print("Ошибка: Невозможно выполнить запрос к БД " . $error);
  die();
}

$lot_item = mysqli_fetch_assoc($lot_item_DB);

if (empty($lot_item)) {
	header("HTTP/1.x 404 Not Found");
	die();
}

$is_auth = rand(0, 1);
$user_name = 'Сергей'; // укажите здесь ваше имя
$user_avatar = 'img/user.jpg';

$page_content = include_template('lot.php', 
  [
    'menu_items' => $menu_items, 
    'lot_item' => $lot_item
  ]);
$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items, 
    'title' => 'Yeticave', 
    'is_auth'=>$is_auth, 
    'user_name'=>$user_name
  ]);
print($layout_content);