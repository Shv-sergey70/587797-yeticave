<?php
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');

$menu_items_query = 'SELECT * FROM categories';
$menu_items_DB = mysqli_query($link, $menu_items_query);
checkDBError($menu_items_DB, $link);

$menu_items = mysqli_fetch_all($menu_items_DB, MYSQLI_ASSOC);

$catalog_items_query = 'SELECT
                        lots.id as ID,
                        lots.name AS lot_name, 
                        lots.start_price AS lot_start_price, 
                        lots.image_url,
                        lots.date_create,
                        lots.date_end, 
                        lots.bet_step, 
                        categories.name AS category_name 
                        FROM lots 
                        JOIN categories 
                        ON lots.adv_category_id = categories.id 
                        WHERE lots.date_end > CURDATE()
                        ORDER BY lots.date_create DESC';
$catalog_items_DB = mysqli_query($link, $catalog_items_query);
checkDBError($catalog_items_DB, $link);

$catalog_items = mysqli_fetch_all($catalog_items_DB, MYSQLI_ASSOC);

$is_auth = rand(0, 1);

$user_name = 'Сергей'; // укажите здесь ваше имя
$user_avatar = 'img/user.jpg';
$page_content = include_template('index.php', 
  [
    'menu_items' => $menu_items, 
    'catalog_items' => $catalog_items
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