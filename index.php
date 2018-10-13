<?php 
declare(strict_types=1);
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_res($menu_items_query, $link, true);

//Запрос на получение лотов
$catalog_items_query = 'SELECT
                        lots.id as ID,
                        lots.name AS lot_name, 
                        lots.start_price AS lot_start_price, 
                        lots.image_url,
                        lots.date_create,
                        lots.date_end as FINISH_DATE, 
                        lots.bet_step, 
                        categories.name AS category_name 
                        FROM lots 
                        JOIN categories 
                        ON lots.adv_category_id = categories.id 
                        WHERE lots.date_end > CURDATE()
                        ORDER BY lots.date_create DESC';
$catalog_items = get_DB_query_res($catalog_items_query, $link, true);

foreach ($catalog_items as $key => $value) {
  if (strtotime($value['FINISH_DATE']) < strtotime('+1 day')) {
      $catalog_items[$key]['IS_LESS_THAN_24_HOUR'] = true;
  } else {
      $catalog_items[$key]['IS_LESS_THAN_24_HOUR'] = false;
  }
}

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