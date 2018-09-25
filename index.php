<?php
require_once('functions.php');
//Создадим 2 массива module2-task1
$menu_items = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$catalog_items = [
    [
        'NAME' => '2014 Rossignol District Snowboard',
        'CATEGORY' => 'Доски и лыжи',
        'PRICE' => '10999',
        'URL' => 'img/lot-1.jpg'
    ],
    [
        'NAME' => 'DC Ply Mens 2016/2017 Snowboard',
        'CATEGORY' => 'Доски и лыжи',
        'PRICE' => '159999',
        'URL' => 'img/lot-2.jpg'
    ],
    [
        'NAME' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'CATEGORY' => 'Крепления',
        'PRICE' => '8000',
        'URL' => 'img/lot-3.jpg'
    ],
    [
        'NAME' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'CATEGORY' => 'Ботинки',
        'PRICE' => '10999',
        'URL' => 'img/lot-4.jpg'
    ],
    [
        'NAME' => 'Куртка для сноуборда DC Mutiny Charocal',
        'CATEGORY' => 'Одежда',
        'PRICE' => '7500',
        'URL' => 'img/lot-5.jpg'
    ],
    [
        'NAME' => 'Маска Oakley Canopy',
        'CATEGORY' => 'Разное',
        'PRICE' => '5400',
        'URL' => 'img/lot-6.jpg'
    ],
];
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