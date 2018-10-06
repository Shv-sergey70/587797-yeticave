<?php
require_once('functions.php');
$link = require_once('db_conn.php');

if (!isset($_GET['ID'])) {
	header("HTTP/1.x 404 Not Found");
  die();
}
$lot_id = intval($_GET['ID']);

$menu_items_query = 'SELECT * FROM categories';
$menu_items_DB = mysqli_query($link, $menu_items_query);
checkDBError($menu_items_DB, $link);

$menu_items = mysqli_fetch_all($menu_items_DB, MYSQLI_ASSOC);

$lot_query = 'SELECT 
							lots.id as ID,
							lots.name as NAME,
							lots.description as DESCRIPTION,
							lots.image_url as IMAGE_URL,
              lots.date_end as FINISH_DATE,
              lots.start_price as START_PRICE,
							categories.name as CATEGORY_NAME,
              MAX(bets.price) as MAX_BET_PRICE
							FROM lots 
							JOIN categories
							ON lots.adv_category_id = categories.id
              LEFT JOIN bets
              ON bets.lot_id = lots.id
							WHERE
              lots.date_end > CURDATE() AND lots.id = '.$lot_id.'
              GROUP BY bets.lot_id';
$lot_item_DB = mysqli_query($link, $lot_query);
checkDBError($lot_item_DB, $link);

$lot_item = mysqli_fetch_assoc($lot_item_DB);
checkForExistanceDBres($lot_item);

$bets_query = 'SELECT
              bets.lot_id,
              bets.id as ID,
              bets.date_create as DATE_CREATE,
              bets.price as PRICE,
              users.name as USER_NAME
              FROM bets
              JOIN users 
              ON bets.user_id = users.id
              WHERE bets.lot_id = '.$lot_id;

$bets_list_DB = mysqli_query($link, $bets_query);
checkDBError($bets_list_DB, $link);

$bets_list = mysqli_fetch_all($bets_list_DB, MYSQLI_ASSOC);

$is_auth = rand(0, 1);
$user_name = 'Сергей'; // укажите здесь ваше имя
$user_avatar = 'img/user.jpg';

$page_content = include_template('lot.php', 
  [
    'menu_items' => $menu_items, 
    'lot_item' => $lot_item,
    'bets_list' => $bets_list
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