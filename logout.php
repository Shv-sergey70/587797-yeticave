<?php
declare(strict_types=1);
require_once('const.php');
session_start();
unset($_SESSION['USER']);
header('Location: '.MAIN_DIR);
die();
