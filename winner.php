<?php
declare(strict_types=1);
require_once('functions.php');
require_once('const.php');
$link = require_once('db_conn.php');
session_start();


//Запрос в БД для получения всех лотов без победителей, с истекшей датой
$lots_winner_query = "SELECT
										lots.id as ID,
										lots.name as NAME,
			              bets.lot_id as LOT_ID,
			              MAX(bets.date_create) as DATE
										FROM bets
										JOIN lots
										ON lots.id = bets.lot_id
										WHERE 
										lots.date_end <= CURDATE() AND
										lots.winner_id IS NULL
										GROUP BY bets.lot_id";
$lots_winner_result = get_DB_query_res($lots_winner_query, $link, true);

// echo "<pre>";
//   var_dump($lots_winner_result);
// echo "</pre>";
// die();


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