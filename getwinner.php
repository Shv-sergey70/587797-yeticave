<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';

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
$email = include_template('email.php', 
  [
    // 'USER'=> $USER
  ]);

// Create the Transport
$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
  ->setUsername('keks@phpdemo.ru')
  ->setPassword('htmlacademy')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Ваша ставка победила'))
  ->setFrom(['keks@phpdemo.ru' => 'Интернет Аукцион "YetiCave"'])
  ->setTo(['shv.sergey70@gmail.com'])
  ->setBody($email, 'text/html')
  ;

// Send the message
// $result = $mailer->send($message);