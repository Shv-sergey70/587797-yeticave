<?php
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;

if (!isset($_GET['ID'])) {
	header("HTTP/1.x 404 Not Found");
  die();
}
$lot_id = intval($_GET['ID']);

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_rows($menu_items_query, $link);

//Запрос на получение лота
$lot_query = 'SELECT 
							lots.id as ID,
							lots.name as NAME,
							lots.description as DESCRIPTION,
							lots.image_url as IMAGE_URL,
              lots.date_end as FINISH_DATE,
              lots.bet_step as PRICE_STEP,
							categories.name as CATEGORY_NAME,
              IFNULL(MAX(bets.price), lots.start_price) as PRICE,
              COUNT(bets.id) as BETS_COUNT
							FROM lots 
							JOIN categories
							ON lots.adv_category_id = categories.id
              LEFT JOIN bets
              ON bets.lot_id = lots.id
							WHERE
              lots.date_end > CURDATE() AND lots.id = '.$lot_id.'
              GROUP BY bets.lot_id';
$lot_item = get_DB_query_row($lot_query, $link);
checkForExistanceDBres($lot_item);
//Определим минимальную ставку
$lot_item['MIN_BET'] = $lot_item['PRICE']+$lot_item['PRICE_STEP'];

//Запрос на получение ставок
$bets_query = 'SELECT
              bets.lot_id,
              bets.id as ID,
              bets.date_create as DATE_CREATE,
              bets.price as PRICE,
              users.name as USER_NAME
              FROM bets
              JOIN users 
              ON bets.user_id = users.id
              WHERE bets.lot_id = '.$lot_id.'
              ORDER BY bets.date_create DESC';
$bets_list = get_DB_query_rows($bets_query, $link);

$page_content = include_template('lot.php', 
  [
    'menu_items' => $menu_items, 
    'lot_item' => $lot_item,
    'bets_list' => $bets_list,
    'USER'=> $USER
  ]);
$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items, 
    'title' => 'Yeticave', 
    'USER'=> $USER
  ]);
print($layout_content);