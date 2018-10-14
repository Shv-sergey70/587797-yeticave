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

//Ограничен доступ не анонимных пользователей
isAuth($USER);

//Запрос в БД для получения ставок пользователя
// $searching_query = "SELECT 
// 										bets.id as ID,
// 										bets.name as NAME,
// 										lots.description as DESCRIPTION,
// 										lots.image_url as IMAGE_URL,
// 			              lots.date_end as FINISH_DATE,
// 			              lots.bet_step as PRICE_STEP,
// 			              lots.author_id as AUTHOR_ID,
// 			              categories.name as CATEGORY_NAME,
// 			              COUNT(bets.lot_id) as BETS_COUNT
// 										FROM bets
// 										JOIN categories
// 										ON lots.adv_category_id = categories.id
// 										LEFT JOIN bets
//           					ON bets.lot_id = lots.id
// 										WHERE 
// 										bets.user_id = $USER['id']
// 										GROUP BY lots.id
// 										ORDER BY lots.date_create ASC
// 										LIMIT ".$pagination['ELEMENT_PER_PAGE']."
// 										OFFSET ".$pagination['OFFSET'];
// $search_result = get_DB_query_res($searching_query, $link, true);



$page_content = include_template('my_lots.php', 
[
  'menu_items' => $menu_items
]);


$layout_content = include_template('layout.php', 
  [
    'content' => $page_content, 
    'menu_items' => $menu_items, 
    'title' => 'Yeticave', 
    'USER'=> $USER
  ]);
print($layout_content);