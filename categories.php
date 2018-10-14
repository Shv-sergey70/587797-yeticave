<?php
declare(strict_types=1);
require_once('functions.php');
require_once('const.php');

if (!isset($_GET['cat_id'])) {
	header('HTTP/1.x 404 Not Found');
	die();
}

$link = require_once('db_conn.php');
session_start();
$USER = isset($_SESSION['USER'])?$_SESSION['USER']:NULL;

//Запрос на получение пунктов меню
$menu_items_query = 'SELECT * FROM categories';
$menu_items = get_DB_query_res($menu_items_query, $link, true);

$category_id = intval($_GET['cat_id']); //ID текущей категории

//Запрос в БД по поисковой фразе, для получения количества лотов
$category_info_query = "SELECT
												COUNT(*) as CNT,
												categories.name as CATEGORY_NAME
												FROM lots
												JOIN categories
												ON lots.adv_category_id = categories.id
												WHERE 
												lots.date_end > CURDATE()
												AND categories.id = $category_id";
$category_info_result = get_DB_query_res($category_info_query, $link, false);
$items_count = (int)$category_info_result['CNT']; //Количество элементов
$category_name = $category_info_result['CATEGORY_NAME']; //Название категории
if (empty($category_info_result['CATEGORY_NAME'])) {
  header("HTTP/1.x 404 Not Found");
  die();
}

$pagination = createPagination(intval($_GET['page'] ?? 1), $items_count, 9); //Создаем пагинацию

//Запрос для поиска лотов по категориям
$items_query = "SELECT 
										lots.id as ID,
										lots.name as NAME,
										lots.description as DESCRIPTION,
										lots.image_url as IMAGE_URL,
			              lots.date_end as FINISH_DATE,
			              lots.bet_step as PRICE_STEP,
			              lots.author_id as AUTHOR_ID,
			              categories.name as CATEGORY_NAME,
			              COUNT(bets.lot_id) as BETS_COUNT
										FROM lots
										JOIN categories
										ON lots.adv_category_id = categories.id
										LEFT JOIN bets
          					ON bets.lot_id = lots.id
										WHERE 
										lots.date_end > CURDATE()
										AND categories.id = $category_id
										GROUP BY lots.id
										ORDER BY lots.date_create ASC
										LIMIT ".$pagination['ELEMENT_PER_PAGE']."
										OFFSET ".$pagination['OFFSET'];
$items_result = get_DB_query_res($items_query, $link, true);

foreach ($items_result as $key => $value) {
	if (strtotime($value['FINISH_DATE']) < strtotime('+1 day')) {
		$items_result[$key]['IS_LESS_THAN_24_HOUR'] = true;
	} else {
		$items_result[$key]['IS_LESS_THAN_24_HOUR'] = false;
	}
}

$page_content = include_template('categories.php', 
  [
    'menu_items' => $menu_items,
    'category_id' => $category_id,
    'category_name' => $category_name,
    'items_result' => $items_result,
    'pagination' => $pagination
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