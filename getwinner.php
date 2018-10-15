<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';

//Запрос в БД для получения всех лотов без победителей, с истекшей датой
$lots_without_winner_query = "SELECT
										lots.id as ID,
										lots.name as NAME,
										lots.date_end as DATE_END
										FROM lots
										WHERE 
										lots.date_end <= CURDATE() AND
										lots.winner_id IS NULL";
$lots_without_winner_result = get_DB_query_res($lots_without_winner_query, $link, true);
if (!empty($lots_without_winner_result)) {
	$i = 0;
	foreach ($lots_without_winner_result as $value) {
		//Получаем ID юзера, установившего самую позднюю ставку на лот
		$bets_winner_query = "SELECT
				              bets.user_id as USER_ID,
				              users.name as USER_NAME,
				              users.email as USER_EMAIL
											FROM bets
											JOIN users
											ON bets.user_id = users.id
											WHERE bets.lot_id = '".$value['ID']."'
											ORDER BY bets.date_create DESC
											LIMIT 1";
	  $bets_winner_result = get_DB_query_res($bets_winner_query, $link, false);
	  if (!empty($bets_winner_result)) {
	  	//Обновляем поле winner_id у лотов
	  	$lots_winner_id_update_query = "UPDATE
												              lots
												              SET
												              lots.winner_id = ".$bets_winner_result['USER_ID']."
																			WHERE lots.id = ".$value['ID'];
			$query_result = mysqli_query($link, $lots_winner_id_update_query);
			if (!$query_result) {
		    print("Ошибка в запросе $query: ".mysqli_error($link));
		    die();
		  }
	  	$lot_info[$i] = $bets_winner_result;
		  $lot_info[$i]['LOT_ID'] = $value['ID'];
		  $lot_info[$i]['LOT_NAME'] = $value['NAME'];
		  $i++;
	  }
	}
	unset($i);
}

if (!empty($lot_info)) {
	// Create the Transport
	$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
	  ->setUsername('keks@phpdemo.ru')
	  ->setPassword('htmlacademy')
	;
	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);

	foreach ($lot_info as $value) {
		$email = include_template('email.php', 
		  [
		    'mail_info'=> $value
		  ]);

		// Create a message
		$message = (new Swift_Message('Ваша ставка победила'))
		  ->setFrom(['keks@phpdemo.ru' => 'Интернет Аукцион "YetiCave"'])
		  ->setTo([$value['USER_EMAIL']])
		  ->setBody($email, 'text/html')
		  ;
		// Send the message
		$result = $mailer->send($message);
	}
}