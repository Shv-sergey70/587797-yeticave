<?php
$link = mysqli_connect('localhost', 'root', 'root', 'yeticave');
if ($link === false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, 'utf8');
return $link;