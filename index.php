<?php
require_once('functions.php');
$link = mysqli_connect('localhost', 'root', 'root', 'yeticave');

if ($link === false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
} else {
    mysqli_set_charset($link, 'utf8');
    $menu_items_query = 'SELECT * FROM categories';
    $menu_items_DB = mysqli_query($link, $menu_items_query);
    if ($menu_items_DB) {
      $menu_items = mysqli_fetch_all($menu_items_DB, MYSQLI_ASSOC);
    } else {
      $error = mysqli_error($link);
      print("Ошибка: Невозможно выполнить запрос к БД " . $error);
    }

    $catalog_items_query = 'SELECT
                            lots.name AS lot_name, 
                            lots.start_price AS lot_start_price, 
                            lots.image_url,
                            lots.date_create,
                            lots.date_end, 
                            lots.bet_step, 
                            -- COUNT(bets.lot_id) AS bets_number, 
                            categories.name AS category_name 
                            FROM lots 
                            -- JOIN bets 
                            -- ON lots.id = bets.lot_id 
                            JOIN categories 
                            ON lots.adv_category_id = categories.id 
                            WHERE lots.date_end > CURDATE()
                            -- GROUP BY bets.lot_id
                            ORDER BY lots.date_create DESC';
    $catalog_items_DB = mysqli_query($link, $catalog_items_query);
    if ($catalog_items_DB) {
      $catalog_items = mysqli_fetch_all($catalog_items_DB, MYSQLI_ASSOC);
    } else {
      $error = mysqli_error($link);
      print("Ошибка: Невозможно выполнить запрос к БД " . $error);
    }
}
$is_auth = rand(0, 1);

$user_name = 'Сергей'; // укажите здесь ваше имя
$user_avatar = 'img/user.jpg';
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
    'is_auth'=>$is_auth, 
    'user_name'=>$user_name
  ]);
print($layout_content);