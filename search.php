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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
	$search_query = trim($_GET['search']);
	if (empty($search_query)) {
		$search_error = 'Строка поиска не должна быть пустой';
		$page_content = include_template('search.php', 
	  [
	    'menu_items' => $menu_items,
	    'error' => $search_error
	  ]);
	} else {
		//Запрос в БД по поисковой фразе
		$safe_search_query = mysqli_real_escape_string($link, $search_query);

		$searching_query = "SELECT 
												lots.id as ID,
												lots.name as NAME,
												lots.description as DESCRIPTION,
												lots.image_url as IMAGE_URL,
					              lots.date_end as FINISH_DATE,
					              lots.bet_step as PRICE_STEP,
					              lots.author_id as AUTHOR_ID,
					              categories.name as CATEGORY_NAME,
												COUNT(bets.id) as BETS_COUNT
												FROM lots
												JOIN categories
												ON lots.adv_category_id = categories.id
												LEFT JOIN bets
              					ON bets.lot_id = lots.id
												WHERE MATCH(lots.name, lots.description)
												AGAINST('$safe_search_query')
												GROUP BY bets.lot_id";
		$search_result = get_DB_query_res($searching_query, $link, true);

		$page_content = include_template('search.php', 
	  [
	    'menu_items' => $menu_items,
	    'search_result' => $search_result,
	    'search_query' => $search_query
	  ]);
	}
} else {
	$page_content = include_template('search.php', 
  [
    'menu_items' => $menu_items,
    'error' => 'Введите Ваш запрос в поисковую строку'
  ]);
}


$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items, 
    'title' => 'Yeticave', 
    'USER'=> $USER
  ]);
print($layout_content);