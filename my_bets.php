<?php
declare(strict_types=1);
require_once('functions.php');
$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_res($menu_items_query, $link, true);

//Ограничен доступ не анонимных пользователей
isAuth($USER);

//Запрос в БД для получения ставок пользователя
$my_bets_query = "SELECT 
										lots.id as ID,
										lots.name as NAME,
										lots.image_url as IMAGE_URL,
										lots.winner_id as WINNER_ID,
										users.contacts as CONTACTS,
										categories.name as CATEGORY_NAME,
			              lots.date_end as FINISH_DATE,
			              bets.price as BET_PRICE,
			              bets.date_create as BET_DATE_CREATE
										FROM bets
										JOIN lots
          					ON bets.lot_id = lots.id
										JOIN categories
										ON lots.adv_category_id = categories.id
										JOIN users
										ON lots.author_id = users.id
										WHERE 
										bets.user_id = ".$USER['id']."
										ORDER BY bets.date_create DESC";
$my_bets_result = get_DB_query_res($my_bets_query, $link, true);

foreach ($my_bets_result as $key => $value) {
	$unix_finish_date = strtotime($value['FINISH_DATE']);
	if ($unix_finish_date > strtotime('+1 day')) {
		$my_bets_result[$key]['LOT_STATUS'] = 'IS_MORE_THAN_24_HOUR';
	} elseif(time() < $unix_finish_date && $unix_finish_date < strtotime('+1 day')) {
		$my_bets_result[$key]['LOT_STATUS'] = 'IS_LESS_THAN_24_HOUR';
	} elseif($value['WINNER_ID'] === $USER['id']) {
		$my_bets_result[$key]['LOT_STATUS'] = 'IS_WIN';
	} else {
		$my_bets_result[$key]['LOT_STATUS'] = 'IS_FINISHED';
	} 
}

$page_content = include_template('my_bets.php', 
[
  'menu_items' => $menu_items,
  'my_bets' => $my_bets_result
]);

$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items,
    'search_query' => $_GET['search']??'', 
    'title' => 'Yeticave', 
    'USER'=> $USER
  ]);
print($layout_content);