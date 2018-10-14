<?php
declare(strict_types=1);
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
$menu_items = get_DB_query_res($menu_items_query, $link, true);

//Запрос на получение лота
$lot_query = 'SELECT 
							lots.id as ID,
							lots.name as NAME,
							lots.description as DESCRIPTION,
							lots.image_url as IMAGE_URL,
              lots.date_end as FINISH_DATE,
              lots.bet_step as PRICE_STEP,
              lots.author_id as AUTHOR_ID,
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
$lot_item = get_DB_query_res($lot_query, $link, false);
checkForExistanceDBres($lot_item);
//Определим минимальную ставку
$lot_item['MIN_BET'] = $lot_item['PRICE']+$lot_item['PRICE_STEP'];

//Запрос на получение ставок
$bets_query = 'SELECT
              bets.lot_id,
              bets.id as ID,
              bets.date_create as DATE_CREATE,
              bets.price as PRICE,
              bets.user_id as AUTHOR_ID,
              users.name as USER_NAME
              FROM bets
              JOIN users 
              ON bets.user_id = users.id
              WHERE bets.lot_id = '.$lot_id.'
              ORDER BY bets.date_create DESC';
$bets_list = get_DB_query_res($bets_query, $link, true);

//Определение доступа к добавлению ставки
$can_create_bet = true;
if (!isset($_SESSION['USER'])) {
  $can_create_bet = false;
} elseif ((int)$lot_item['AUTHOR_ID'] === (int)$_SESSION['USER']['id']) {
  $can_create_bet = false;
} elseif(strtotime($lot_item['FINISH_DATE']) < time()) {
  $can_create_bet = false;
} elseif (!empty($bets_list)) {
  foreach ($bets_list as $value) {
    if ((int)$value['AUTHOR_ID'] === (int)$_SESSION['USER']['id']) {
      $can_create_bet = false;
    }
  }
}

//Добавление новой ставки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_bet = $_POST;
  $errors = [];
  $options = ['options' => ['min_range' => $lot_item['MIN_BET']]];
  if (empty($new_bet['COST'])) {
    $errors['COST'] = 'Это поле надо заполнить';
  } else if (!filter_var($new_bet['COST'], FILTER_VALIDATE_INT, $options)) {
    $errors['COST'] = 'Ваша ставка должна быть целым числом, больше минимальной ставки';
  }

  if (count($errors)) {
    $page_content = include_template('lot.php', 
    [
      'menu_items' => $menu_items,
      'lot_item' => $lot_item,
      'bets_list' => $bets_list,
      'new_bet' => $new_bet,
      'errors' => $errors,
      'can_create_bet' => $can_create_bet
    ]);
  } else {
    //Запрос на добавление новой ставки
    $user_id = intval($_SESSION['USER']['id']);
    $lot_id = intval($lot_item['ID']);
    $safe_COST = intval($new_bet['COST']);

    $bet_add_query = "INSERT INTO bets
                      SET
                      date_create = NOW(),
                      price = '$safe_COST',
                      user_id = '$user_id',
                      lot_id = '$lot_id'";
    put_DB_query_row($bet_add_query, $link);
    header('Location: lot.php?ID='.$lot_id);
    die();
  }
} else {
  $page_content = include_template('lot.php', 
  [
    'menu_items' => $menu_items, 
    'lot_item' => $lot_item,
    'bets_list' => $bets_list,
    'can_create_bet' => $can_create_bet
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