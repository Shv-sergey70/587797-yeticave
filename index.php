<?php
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_rows($menu_items_query, $link);

//Запрос на получение лотов
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
$catalog_items = get_DB_query_rows($catalog_items_query, $link);

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
    'USER'=> $USER
  ]);
print($layout_content);