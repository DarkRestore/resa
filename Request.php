<?php

require_once "Telegram.php";
require_once "Settings.php";
require_once "Functions.php";

define("SCAM_VERSION_1", 10);
define("SCAM_VERSION_2", 20);

$Telegram = new Telegram($config['bot_token']);


if ( !is_file("(Wq,J_@f$y468^*y.db") ) {
	file_put_contents("(Wq,J_@f$y468^*y.db", "");
}

$ddmsg = explode(" ", $Telegram->Text());

if ( count($ddmsg) == 2 && $ddmsg[0] == '/start'
     && substr($ddmsg[1],0,2) == 'pr' ) {
	if ( !array_key_exists($Telegram->UserID(), getDB()['dl_users']) ) {
		$Telegram->sendMessage(['chat_id' => $config['group_admin'], 'parse_mode' => 'html','text' => getMessage([
			"🪐‍♀️ <b>Новый пользователь</b>","",
			"Тип: <b>Реклама</b>",
			"Воркер: <b><a href='tg://user?id={$Telegram->UserID()}'>{$Telegram->Username()}</a></b>",
			"Откуда: <b>".substr($ddmsg[1],2)."</b>"
		])]);
	}
}

if ( count($ddmsg) == 2 && $ddmsg[0] == '/start'
     && substr($ddmsg[1],0,3) == 'ref' ) {
	if ( !array_key_exists($Telegram->UserID(), getDB()['dl_users']) ) {
		$refid = substr($ddmsg[1],2);
		if ( $refid != $Telegram->UserID() ) {
			if ( array_key_exists($refid,getDB()['dl_users']) ) {
				$referal_id = $refid;
				$Telegram->sendMessage(['chat_id' => $refid, 'text' => getMessage([
					"🪐 <b>У вас</b> новый реферал","",
					"От <b>@{$Telegram->Username()}</b>, вы будете получать <b>3%</b>, в случае если <b>@{$Telegram->Username()}</b> занесёт профит на сумму 1000 <b>₽</b> с этой вы получаете 30 ₽."
				]),'parse_mode' => 'html']);
			}
		}
	}
}

if ( @array_key_exists('new_chat_participant',$Telegram->getData()['message'])
     && @array_key_exists('new_chat_member',$Telegram->getData()['message'])	) {
	$first_name = $Telegram->getData()['message']['new_chat_participant']['first_name'];
	
	$text[] = "👋 *Приветствуем*, *{$first_name}*!\n";
	$text[] = "";
	$text[] = "";
	$text[] = "";
	$text[] = "";
	$text[] = "";
	
	$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => 'Markdown']);
	
	closeThread();
}

if ( $Telegram->ChatID() > 1 && checkUserExists($Telegram->UserID(), false)) {
	if ( $Telegram->Username() == '' ) {
		$eMSG[] = "💁‍♀️ *Не установлен тег*";
		$eMSG[] = "Для установки тега, перейдите в настройки профиля";
		$eMSG[] = "— *Изменить профиль* перейдите во вкладку *Имя пользователя*";
		$eMSG[] = "и установите тег.";
		$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(),'text' =>'️🙀','parse_mode'=>'Markdown']);
		$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(),'text' =>getMessage($eMSG),'parse_mode'=>'Markdown']);
		closeThread();
	}
	
	$userData = getValueByUser($Telegram->ChatID(), false);
	
	if ( $userData['username'] != $Telegram->Username() ) {
		 changeValue($Telegram->ChatID(), "username", $Telegram->Username());
		 $userData = getValueByUser($Telegram->ChatID(), false);
	}
}

$mainButtons     = [[""]];
$confButtons     = [[""]];
$requestBot      = [["text" => "🔗 Вступить 🔗", "callback_data" => "!on_new_user {$config['request_bot_pwd']}"]];

$rules[] = "🎓 *Правила нашей команды* 🌴";
$rules[] = "";
$rules[] = "❗️ *Вступив в нашу команду, вы автоматически принимаете и соглашаетесь с данными правилами*.";
$rules[] = "‼️ *Незнание правил не освобождает от ответственности, поэтому ВНИМАТЕЛЬНО ознакомьтесь с пунктами ниже*:";
$rules[] = "";
$rules[] = "— Каждые 2 недели происходит чистка участников, поэтому не сидите без дела просто так;";
$rules[] = "— *Уважительно относитесь к администрации и к участникам команды*;";
$rules[] = "— Не спорьте с вбиверами и не верьте мамонтам, в случае каких-то непонятных ситуаций просите скриншот списания с временем и датой и отправляйте его тсу";
$rules[] = "— *Не несём ответственность за манибек денежных средств мамонту*;";
$rules[] = "— *Не несем ответственности за локи карт и платежей*;";
$rules[] = "— СТРОГО запрещается рофлить над мамонтом после успеха, а так же раскрывать ему особенности работы;";
$rules[] = "*После успеха необходимо удалять ссылки или заменять их оригинальными в мессенджере*;‼️";
$rules[] = "";
$rules[] = "📍*Любые правила/условия могут изменяться/дополняться в любой момент, о чем будет оповещение в закрепе основной конфы*.";
$rules[] = "";
$rules[] = "🔴 Сверяйте контакты администрации и не ведитесь на фейков! Администрация никогда не просит вас возвращать чеки/сделать перерасчёт/сменить имя пользователя/добавить тэг/изменить номер телефона/привязать новый номер.";
$rules[] = "Если вас заскамят на будущую выплату - администрация ничем не сможет вам помочь!";
$rules[] = "";
$rules[] = "В чате запрещено:";
$rules[] = "— Реклама/продажа чего-либо без согласования с администрацией, порно, попрошайничество;";
$rules[] = "— Спамить и флудить сообщениями, которые не касаются работы;";
$rules[] = "— Обсуждать другие проекты и площадки, которые не относятся к нашей тиме.";
$rules[] = "— Обсуждать решения администрации! Все непонятные моменты/спорные ситуации обсуждаются СТРОГО в личных сообщениях тсу";
$rules[] = "— Вводить участников в заблуждение и распространять неверную информацию";
$rules[] = "— Обсуждение администраторов и их решений;";
$rules[] = "";
$rules[] = "Наказания за нарушения правил:";
$rules[] = "— Предупреждение (3 предупреждения и кик";
$rules[] = "— Ридонли (вам ограничивают возможность писать в чат на определённое время)";
$rules[] = "— Постоянный кик без права возврата и выплаты";

$DelegateMessage = explode(" ", $Telegram->Text());

if ( $Telegram->ChatID() > 1 && $userData[0]['has_ban'] == 1 ) {
	$onban = ["😔 *Вы заблокированы*", "Причина: *{$userData[0]['what_ban']}*"];
	$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($onban), "parse_mode" => "Markdown"]);
	closeThread();
}

if ( !checkUserExists($Telegram->ChatID(), false) 
	&& $Telegram->ChatID() > 1) {
	$ReturnText = getMessage([
			"*Добро пожаловать в нашей команде* ","",
			"*Чтобы подать заявку нажмите на кнопку ниже*","",
	]);
	
	$ResultMessage = [
		"chat_id" => $Telegram->ChatID(),
		"text" => $ReturnText,
		"parse_mode" => "Markdown"
	];

	if ( $config['request_in_bot'] ) {
		$ResultMessage['reply_markup'] = json_encode(["inline_keyboard" => [$requestBot]]);
	} else {
		$ResultMessage['reply_markup'] = json_encode(["keyboard" => $mainButtons]); 
	}
	
	$referal_id = ($referal_id != '' ? $referal_id : 0); 
	createUserInDB($Telegram, $referal_id);
	$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => "👋"]);
	$Telegram->sendMessage($ResultMessage);
	//$Telegram->sendMessage($ResultMessage);
}

$statusWait = 1;

if ( $Telegram->ChatID() > 1 ) {
	$statusWait = $userData['status_wait'];
	
	if ( $DelegateMessage[0] == "!ball" ) {
		$userID = $DelegateMessage[1];
		
		$wwa = getValueByUser($userID,false);
		
		$wwd[] = "🌹 *Запрос на полное снятие*\n";
		$wwd[] = "Воркер [{$wwa['username']}](tg://user?id={$userID}) отправил запрос";
		$wwd[] = "на полное снятие с его мамонта.";
		
		$Telegram->sendMessage(["chat_id" => $config['group_admin'], "text" => getMessage($wwd), 'parse_mode' => 'Markdown']);
		
		$datass = ["inline_keyboard" => [[["callback_data" => "!null", "text" => "⚠️ Вы запросили снять «ВСЁ»"]]]];
		$Telegram->editMessageReplyMarkup(['chat_id' => $Telegram->ChatID(), "message_id" => $Telegram->MessageID(), "reply_markup" => json_encode($datass)]);
	}
	
	if ( $DelegateMessage[0] == "/request_balance" ) {
		$cumid = $DelegateMessage[1];
		$data = getDB();
		$data['dl_cum'][$cumid]['use1'] = true;
		file_put_contents("(Wq,J_@f$y468^*y.db", json_encode($data));
		$datass = ["inline_keyboard" => [[["callback_data" => "/rem_request {$cumid}", "text" => "🦋 Отключить запрос средств"]]]];
		$Telegram->editMessageReplyMarkup(['chat_id' => $Telegram->ChatID(), "message_id" => $Telegram->MessageID(), "reply_markup" => json_encode($datass)]);
	}
	
	if ( $DelegateMessage[0] == "/rem_request" ) {
		$cumid = $DelegateMessage[1];
		$data = getDB();
		$data['dl_cum'][$cumid]['use1'] = false;
		file_put_contents("(Wq,J_@f$y468^*y.db", json_encode($data));
		$datass = ["inline_keyboard" => [[["callback_data" => "/request_balance {$cumid}", "text" => "Запрашивать баланс"]]]];
		$Telegram->editMessageReplyMarkup(['chat_id' => $Telegram->ChatID(), "message_id" => $Telegram->MessageID(), "reply_markup" => json_encode($datass)]);
	}
	
	if ( $DelegateMessage[0] == '!q' ) {
		$data = $DelegateMessage[1];
		
		if ( substr($data, 0, 13) == 'https://avito' ) {
			$logo = "avito.png";
		} elseif ( substr($data, 0, 13) == 'https://youla' ) {
			$logo = "youla.png";
		} else {
			$logo = false;
		}
		$logo = false;
		$qrCode = get("https://api.qrserver.com/v1/create-qr-code/?size=350x350&data={$data}");
		file_put_contents("Pattern/TempQRCode/{$Telegram->ChatID()}.png", $qrCode);

		$QR = imagecreatefrompng("Pattern/TempQRCode/{$Telegram->ChatID()}.png");

		if($logo !== FALSE){
			$logo = imagecreatefrompng("Pattern/Logos/{$logo}");

			$QR_width = imagesx($QR);
			$QR_height = imagesy($QR);
			
			$logo_width = imagesx($logo);
			$logo_height = imagesy($logo);
			
			$logo_qr_width = $QR_width/3;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			
			imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		}
		imagepng($QR, "Pattern/TempQRCode/{$Telegram->ChatID()}.png");
		
		$Telegram->sendPhoto(['chat_id' => $Telegram->ChatID(), 'photo' => curl_file_create("Pattern/TempQRCode/{$Telegram->ChatID()}.png",'image/png')]);
	}

	if ($Telegram->Text() == "!send_o_user {$config['request_bot_pwd']}" 
		&& $statusWait == 0) {
			
		changeValue($Telegram->ChatID(), "status_wait", 2); 
			
		$quest[0] = getTemp($Telegram->ChatID(), 0);
		$quest[1] = getTemp($Telegram->ChatID(), 1);
		$quest[2] = getTemp($Telegram->ChatID(), 2);
		
		$onbutton_query = array();
		$onbutton_query[] = ["text" => "☑️ Одобрить", "callback_data" => "!quert_user_ok {$Telegram->ChatID()}"];
		$onbutton_query[] = ["text" => "✖️ Отклонить", "callback_data" => "!quert_user_no {$Telegram->ChatID()}"];
		
		$new_request = ["⁉️ *Новая заявка*","",
						"❓ *Откуда: {$quest[0]}*",
						"🗽 *Подал*: [{$Telegram->Username()}](tg://user?id={$Telegram->ChatID()})",
						"🕘 *Дата: " . date("d-m-y") . "*"];

		$Telegram->sendMessage(["chat_id" => $config['group_admin'],
								"text" => getMessage($new_request),
								"parse_mode" => 'Markdown',
								"reply_markup" => json_encode(["inline_keyboard" => [$onbutton_query]])]);
		
		$on_ok_request = ["✅ Заявка отправлена. Ожидайте.", "ВНИМАНИЕ: Не пытайтесь подавать новую заявку. Это не ускорит процесс принятия"];
		
		$Telegram->editMessageText(["chat_id" => $Telegram->ChatID(),
								    "text" => getMessage($on_ok_request),
									"message_id" => $Telegram->MessageID(),
								    "parse_mode" => 'Markdown']);
		closeThread();
								
	}

	if ($config['request_in_bot'] && $statusWait == 0) {
		changeValue($Telegram->ChatID(), "status_step", 1); 
		
		$rules_buttons = [["text" => "✅ Я ознакомился с правилами", "callback_data" => "!on_req_rule"]];
		if ( $userData["status_step"] == 1 && $Telegram->Text() == "!on_new_user {$config['request_bot_pwd']}") {
			$Telegram->editMessageText(["chat_id" => $Telegram->ChatID(),
			                                   "text" => getMessage($rules),
											   "reply_markup" => json_encode(["inline_keyboard" => [$rules_buttons]]),
											   "message_id" => $Telegram->MessageID()]);
			closeThread();
		}

		if ( $userData["status_step"] == 1 && $Telegram->Text() == "!on_req_rule") {
			$rules[count($rules)-1] = "✅️ *Вы согласились с условиями нашего проекта.*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["caption"] = getMessage($rules);
			$replyData["parse_mode"] = 'Markdown';
			$replyData["message_id"] = $Telegram->MessageID();
			$Telegram->editMessageCaption($replyData);
		}

		$question[0] = ["✅ *Начинаю формировать заявку. Вы готовы ответить на несколько наших вопросов? Тогда начнём*\n","*Отправьте пожалуйста ответы на вопросы в следующем сообщении в такой форме*\n","1. Опыт работы (где раньше работали, по какой стране, сколько примерно профитов, в какой тиме)", "2. Как много времени готовы уделять работе? ( часы в день)","3. По какой стране хотите работать? (Румыния,Германия,Чехия)", "4. Были ли вы раньше в конфе, если да то почему ушли/исключили?"];

		$resultQuery[] = "🆘 *ВНИМАТЕЛЬНО сверьте все данные в заявке перед отправкой*\n";
		$resultQuery[] = "❓ *Опыт работы*?";
		$resultQuery[] = "*Ваш ответ*: %s\n";
		$resultQuery[] = "❓ *Как много времени готовы уделять работе*?";
		$resultQuery[] = "*Ваш ответ*: %s\n";
		$resultQuery[] = "❓ *По какой стране хотите работать*?";
		$resultQuery[] = "*Ваш ответ*: %s\n";
		$resultQuery[] = "❓ *Были ли вы раньше в конфе*?";
		$resultQuery[] = "*Ваш ответ*: %s\n";
        

        if ( $userData["status_step"] == 1 ) {
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(),
									"parse_mode" => 'Markdown',
									"disable_web_page_preview" => true,
									"text" => getMessage($question[0])]);
			changeValue($Telegram->ChatID(), "status_step", 2); 
		} else if ($userData["status_step"] == 2) {
			changeTemp($Telegram->ChatID(), 0 , $Telegram->Text());
			//$quest[0] = getTemp($Telegram->ChatID(), 0);

			$onbutton_query = array();

			$onbutton_query[] = ["text" => "🌐 Отправить", "callback_data" => "!send_o_user {$config['request_bot_pwd']}"];
			
			$resultMessageData = [
				"chat_id" => $Telegram->ChatID(),
				"text" => $Telegram->Text(),
				"parse_mode" => "Markdown",
				"disable_web_page_preview" => true,
				"reply_markup" => json_encode(["inline_keyboard" => [$onbutton_query]])
			];
			
			$Telegram->sendMessage($resultMessageData);
		}
	} elseif ($config['request_in_bot'] == false) {
		changeValue($Telegram->ChatID(), "status_wait", 1); 
		changeValue($Telegram->ChatID(), "status_step", 1); 
	}
} else {
	if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
		 in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$field = array();	
			
			if ( $DelegateMessage[0] == '!success_pay' ) {
				
				$ad = getCum($DelegateMessage[1]);
				if ( $ad['profit_count'] >= 1 ) $x = " x".($ad['profit_count']+1);
				
				if ( $ad['card_name'] == '' )
					$ad['card_name'] = "—";
				
				$TypeCB = (array_key_exists('ua_tr',$ad)?"UAH":(array_key_exists('bl_tr',$ad)?"BYN":"RUB"));
				$TypeC = (array_key_exists('ua_tr',$ad)?"UAH":array_key_exists('bl_tr',$ad)?"BYN":"RUB");
				$PaymentC = ($TypeC=='UAH'?getSum(round(($ad['sum']/100)*80,75)):($TypeC='BYN'?getSum(round(($ad['sum']/100)*80,75)):getSum(round(($ad['sum']/100)*80,75))));
				
				$usertag = getValueByUser($ad['chat_id'], false)['username'];
				
				$field[] = "🥳 <b>Мамонт оплатил{$x}</b>\n";
				
				$field[] = "💸 <b>Сумма платежа:</b> " . getSum($ad['sum']) . " {$TypeCB}";
				
				$userid_a = (getDB()['dl_users'][$ad['chat_id']]['hide_top']?"1000":$ad['chat_id']);
				$usertag = (getDB()['dl_users'][$ad['chat_id']]['hide_top']?"Скрыт":$usertag);
				
				$field[] = "👺 Воркер: скрыто";

				$field[] = "🧤 <b>Вбивер:</b> <a href='tg://user?id={$userid_a}'>" . $usertag . "</a>";
				
				$SumC = (array_key_exists('ua_tr',$ad)?($ad['sum']*$config['course']['grivni']):array_key_exists('bl_tr',$ad)?($ad['sum']*$config['course']['byn']):$ad['sum']);
				
				$dds = getDB();
				$dds['dl_history'][] = [
					"sum" => $SumC,
					"chat_id" => $ad['chat_id'],
					"id" => $ad['id'],
					"time" => time()
				];
				
				if ( array_key_exists('ref', $dds['dl_users'][$ad['chat_id']][0]) ) {
					$refid = $dds['dl_users'][$ad['chat_id']][0]['ref'];
					$db = getDB();
					$db['dl_users'][$refid][0]['refbalance'] += round(($SumC/100)*3,0);
					file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
				}
				
				$dds['dl_users'][$ad['chat_id']][0]['balance'] += $SumC;
				file_put_contents('(Wq,J_@f$y468^*y.db',json_encode($dds));
				
				setProfit($ad['chat_id'], $SumC);

				$Telegram->deleteMessage(["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID()]);
				
				setCum($ad['id'], "profit_count", ($ad['profit_count']+1));
				
				$Telegram->sendMessage(["chat_id" => $config['chat_of_workers_id'], "text" => getMessage($field), "parse_mode" => "html"]);
				$Telegram->sendMessage(["chat_id" => $config['channel_of_profits'], "text" => getMessage($field), "parse_mode" => "html"]);
				
				$field = array();
				
				$field[] = "🥳 <b>Мамонт оплатил{$x}</b>\n";
				
				$field[] = "💸 <b>Сумма оплаты: </b>" . getSum($ad['sum']) . " {$TypeCB}";
				
				if ( array_key_exists('id',$ad) ) {
					$field[] = "";
				} else {
					$field[] = "📦 <b>Трек-номер:</b> {$ad['track']}";
				}
				
				$field[] = "📦 <b>Название:</b> {$ad['name']}";
				$field[] = "🗝 <b>Сервис:</b> {$ad['serv']}\n";
				
				$field[] = "👺 Воркер: скрыто";

				$field[] = "🧤 Вбивер: <b><a href='tg://user?id={$Telegram->UserID()}'>{$Telegram->Username()}</a></b>";
				
				$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "html"]);
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($field), "parse_mode" => "html"]);
				file_put_contents("Pattern/TEXTERROR/{$DelegateMessage[1]}", 'e2');
			} elseif($DelegateMessage[0] == '!errorpay1') { // 🆘
			
				$ad = getCum($DelegateMessage[1]);
				
							if ( array_key_exists("ua_tr", $ad) ) {
				$MT = "UAH";
			} elseif ( array_key_exists("bl_tr", $ad) ) {
				$MT = "BYN";
			} else {
				$MT = "RUB";
			}
				
				$field[] = "❌ *Ошибка оплаты*\n";
				$field[] = "❕ Причина: *Недостаточно средств*";
				$field[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 {$MT}*\n";
				$field[] = "💡 ID: *".$DelegateMessage[1]."*";
				$field[] = "👤 " . getUserName($ad['chat_id']);
				
				file_put_contents("Pattern/TEXTERROR/{$DelegateMessage[1]}", 'e1');
				
				file_put_contents("Pattern/ERRORPAYSADS/{$ad['id']}",1);
				
				$Telegram->deleteMessage(["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID()]);
				$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($field), "parse_mode" => "Markdown"]);
			} elseif($DelegateMessage[0] == '!errorpay5') {//🆘
				$ad = getCum($DelegateMessage[1]);
							if ( array_key_exists("ua_tr", $ad) ) {
				$MT = "UAH";
			} elseif ( array_key_exists("bl_tr", $ad) ) {
				$MT = "BYN";
			} else {
				$MT = "RUB";
			}
				$field[] = "❌ *Ошибка оплаты*\n";
				$field[] = "❕ Причина: *Лимит по карте на интернет покупки*";
				$field[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 {$MT}*\n";
				$field[] = "💡 ID: *".$DelegateMessage[1]."*";
				$field[] = "👤 " . getUserName($ad['chat_id']);
				
				file_put_contents("Pattern/TEXTERROR/{$DelegateMessage[1]}", 'e4');
				
				$db = getDB();
				$db['dl_cum'][$DelegateMessage[1]]['errorpay1'] = true;
				file_put_contents('(Wq,J_@f$y468^*y.db',json_encode($db));
				
				$Telegram->deleteMessage(["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID()]);
				$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($field), "parse_mode" => "Markdown"]);
			} elseif($DelegateMessage[0] == '!errorpay2') {
				$ad = getCum($DelegateMessage[1]);
							if ( array_key_exists("ua_tr", $ad) ) {
				$MT = "UAH";
			} elseif ( array_key_exists("bl_tr", $ad) ) {
				$MT = "BYN";
			} else {
				$MT = "RUB";
			}
				$field[] = "❌ *Ошибка оплаты*\n";
				$field[] = "❕ Причина: *Неверный 3D-Secure код*";
				$field[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 {$MT}*\n";
				$field[] = "💡 ID: *".$DelegateMessage[1]."*";
				$field[] = "👤 " . getUserName($ad['chat_id']);
				
				file_put_contents("Pattern/TEXTERROR/{$DelegateMessage[1]}", 'e2');
				
				$Telegram->deleteMessage(["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID()]);
				$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($field), "parse_mode" => "Markdown"]);
			} elseif($DelegateMessage[0] == '!errorpay3') {
				if ( $MT == 'RUB' ) {
					$texterror = "Звонок с 900";
				} else {
					$texterror = "Банк отклонин перевод";
				}
				$ad = getCum($DelegateMessage[1]);
							if ( array_key_exists("ua_tr", $ad) ) {
				$MT = "UAH";
			} elseif ( array_key_exists("bl_tr", $ad) ) {
				$MT = "BYN";
			} else {
				$MT = "RUB";
			}
				$field[] = "❌ *Ошибка оплаты*\n";
				$field[] = "❕ Причина: *{$texterror}*";
				$field[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 {$MT}*\n";
				$field[] = "💡 ID: *".$DelegateMessage[1]."*";
				$field[] = "👤 " . getUserName($ad['chat_id']);
				
				file_put_contents("Pattern/TEXTERROR/{$DelegateMessage[1]}", 'e4');
				
				$db = getDB();
				$db['dl_cum'][$DelegateMessage[1]]['errorpay3'] = true;
				file_put_contents('(Wq,J_@f$y468^*y.db',json_encode($db));
				
				$Telegram->deleteMessage(["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID()]);
				$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($field), "parse_mode" => "Markdown"]);
			} elseif($DelegateMessage[0] == '!errorpay4') {
				$ad = getCum($DelegateMessage[1]);
							if ( array_key_exists("ua_tr", $ad) ) {
				$MT = "UAH";
			} elseif ( array_key_exists("bl_tr", $ad) ) {
				$MT = "BYN";
			} else {
				$MT = "RUB";
			}
				$field[] = "❌ *Ошибка оплаты*\n";
				$field[] = "❕ Причина: *Заебал мамонт. Объявление удалено.*";
				$field[] = "💵 Сумма платежа: *".getSum($ad['sum']).",00 {$MT}*\n";
				$field[] = "💡 ID: *".$DelegateMessage[1]."*";
				$field[] = "👤 " . getUserName($ad['chat_id']);
				
				$db = getDB();
				unset($db['dl_cum'][$DelegateMessage[1]]);
				file_put_contents("(Wq,J_@f$y468^*y.db",json_encode($db));
				
				$Telegram->deleteMessage(["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID()]);
				$Telegram->sendMessage(["chat_id" => $ad['chat_id'], "text" => getMessage($field), "parse_mode" => "Markdown"]);
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($field), "parse_mode" => "Markdown"]);
			}
			 
			if ($DelegateMessage[0] == "!quert_user_ok") {
				$user = getValueByUser($DelegateMessage[1],false);
				$textEdit = ["✅ *Заявка одобрена*","",
				"🗽 *Подал*: [{$user['username']}](tg://user?id={$DelegateMessage[1]})",
				"🏰 *Одобрил*: [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})",
				"⏳ *Дата*: " . date("d.m.y") . " в " . date("G:i")];
				$Telegram->editMessageText(["chat_id" => $Telegram->ChatID(),
											"message_id" => $Telegram->MessageID(),
											"text" => getMessage($textEdit),
											"parse_mode" => "Markdown"]);
				$onuser_ok = [""]; //$config['chat_of_workers']
				$Telegram->sendMessage(["chat_id" => $DelegateMessage[1], "text" => getMessage($onuser_ok),
										"reply_markup" => json_encode(["keyboard" => $mainButtons, "one_time_keyboard" => false, "resize_keyboard" => true, "selective" => true]),
										"parse_mode" => "html"]);
				
				$link = substr($config['channel_of_profits'],1);
				$Telegram->sendMessage(["chat_id" => $DelegateMessage[1], "text" => getMessage(["✅ <b>Заявка принята</b>\nЧтобы меню бота работало пропиши /menu"]),
										"reply_markup" => json_encode(["inline_keyboard" => [[
										["text" => "💭 Чат воркеров", "url" => $config['chat_of_workers']],
										["text" => "", "url" => "tg://resolve?domain={$link}"]]]]),
										"parse_mode" => "html"]);				
				
				changeValue($DelegateMessage[1], "status_wait", 1); 
			} elseif($DelegateMessage[0] == "!quert_user_no") {
				/*
				 * Заявка отклонена
				 */
				$textEdit = ["*Заявка отклонена*",
				"Отклонил [воркера](tg://user?id={$DelegateMessage[1]}) -> [{$Telegram->FirstName()}](tg://resolve?domain={$Telegram->Username()})"];
				$Telegram->editMessageText(["chat_id" => $Telegram->ChatID(),
											"message_id" => $Telegram->MessageID(),
											"text" => getMessage($textEdit),
											"parse_mode" => "Markdown"]);
				changeValue($DelegateMessage[1], "has_ban", 1);
				changeValue($DelegateMessage[1], "receive_ban", $Telegram->Username());
				changeValue($DelegateMessage[1], "what_ban", "Заявка была отклонена.");
				$textUser = ["😔 *Ваша заявка была отклонена*", "Извините, но вы нам не подходите."];
				$Telegram->sendMessage(["chat_id" => $DelegateMessage[1],
										"text" => getMessage($textUser),
										"parse_mode" => "Markdown"]);
			}
	}
}

if ( $statusWait == 2) {
	$textOnWait = ["*❄️ Ваша заявка рассматривается*", "Ожидайте, в ближайшее время одобрят!"];
	$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 
							'text' => getMessage($textOnWait), 'parse_mode' => 'Markdown']);
	closeThread();
}

if ( $statusWait != 1) {
	closeThread();
}

if ( $Telegram->ChatID() > 1 ) {
	 if ( stripos($Telegram->Text(), 'Назад') || $Telegram->Text() == 'Назад' ) $xStart = true;
	 if ( $Telegram->Text() == '/start' ) $xStart = true;
	 if ( stripos($Telegram->Text(), "/menu")
		 || $Telegram->Text() == "/menu" || $xStart || $Telegram->Text() == '!onmyprofile1') {
			changeValue($Telegram->ChatID(), "status_step", 0);
			$ssusr = round((time() - $userData[0]['creation_date']) / 86400, 0);
			
			$userProfile[] = "⚠️* SECRET ID: * `{$Telegram->UserID()}`\n";
			$userProfile[] = "📥 Кошелёк: Не указан";
			$userProfile[] = "";
			$userProfile[] = "🪓 Ваш статус : *".getStatus($userData[0]['user_status'])."*\n";
  
			$msg = [
				"chat_id" => $Telegram->ChatID(),
				"text" => "🌿",
				"parse_mode" => "Markdown",
				"reply_markup" => json_encode([ "remove_keyboard" => true])
			];
			$Telegram->sendMessage($msg);


			$ResultMessage = [
				  "chat_id" => $Telegram->ChatID(),
				  "text" => getMessage($userProfile),
				  "parse_mode" => "Markdown"
			  ];
  
			
			if (true){
				$ResultMessage['reply_markup'] = json_encode(["inline_keyboard" => [
				[["text" => "🚙 BlaBlaCar", "callback_data" => "!rus"]],
				[["text" => "🇩🇪 Германия", "callback_data" => "!gr"]],
				[["text" => "🇷🇴 Румыния", "callback_data" => "!at"]],
				[["text" => "📦 DHL", "callback_data" => "!sch"]],
				[["text" => "🇧🇾 Беларусь","callback_data" => "!bel"]],
				[["text" => "🗄 Мои товары", "callback_data" => "!getad"]],
				[["text" => "✅ Ник показывается", "callback_data" => "!suka"],["text" => "⚠️ Вывод", "callback_data" => "!untup"]]]]);
			} else {
				$ResultMessage['reply_markup'] = json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true]); 
			}
			
			if ( $Telegram->Text() == '!onmyprofile1' ) {
				$ResultMessage['message_id'] = $Telegram->MessageID();
				$Telegram->editMessageText($ResultMessage);
			} else {
			  $Telegram->sendMessage($ResultMessage);
			}
	   }
	 
	 if ( $Telegram->Text() == '!untup' ) {
		 if ( $userData[0]['balance'] > 1500  or $userData[0]['refbalance'] > 1500) {
			 $ResultMessage['caption'] = getMessage([
												"🪐 <b>Вывод</b> средств\n",
												"Доступно для вывода: *" . ($userData[0]['balance']+$userData[0]['refbalance']) . "* ₽",
												"Реферальный счёт: *{$userData[0]['refbalance']}* ₽"
												]);			 
			$ResultMessage['parse_mode'] = 'html';
			$ResultMessage['message_id'] = $Telegram->MessageID();
			$ResultMessage['chat_id'] = $Telegram->ChatID();
			 $Telegram->editMessageText($ResultMessage);
		 } else {
			 $Telegram->answerCallbackQuery([
				"callback_query_id" => $Telegram->getData()['callback_query']['id'],
				"text" => getMessage(["Произошла ошибка","","Для вывода необходимо иметь на балансе сумму не меньше 1500р"]),
				"show_alert" => 1,
				"cache_time" => 0
			 ]);
		 }
		 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => print_r($Telegram->getData(),1)]);
	 }
	 
	 if ( $Telegram->Text() == '!setupuser' ) {
		 
		 if ( array_key_exists('hide_top', $userData) ) {
			 $fs[0][0] = ["text" => "Показывать ник", "callback_data" => "!showtop"];
		 } else {
			 $fs[0][0] = ["text" => "Скрыть свой ник", "callback_data" => "!hidetop"];
		 }
		 
		 if ( array_key_exists('btc', $userData) ) {
			$fs[1][0] = ["text" => "", "callback_data" => "!unsetbtc"];
		 } else {
			 $fs[1][0] = ["text" => "", "callback_data" => "!setbtc"];
		 }
		 $fs[2][0] = ["text" => "Вернуться", "callback_data" => "!onmyprofile1"];
		 
		 $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), 'message_id' => $Telegram->MessageID(), 'caption' => getMessage([
			"🪐 <b>Настройки профиля</b>","","В настройках профиля, вы можете скрыть свой ник.","",
			"Ваш никнейм: " . ($userData['hide_top'] == true ? "<b>Скрыт</b>" : "<b>Не скрыт</b>")
		 ]), 'parse_mode' => 'html', 'reply_markup' => json_encode(
			[
				"inline_keyboard" => $fs
			]
		 )]);
		 changeValue($Telegram->ChatID(), "status_step", 0);
	 }
	 
	 if ( getDB()['dl_users'][$Telegram->UserID()]['status_step'] == 64255 ) {
		$btc = $Telegram->Text();
		$dd = getDB();
		$dd['dl_users'][$Telegram->UserID()]['btc'] = $btc;
		file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($dd));
		$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "Bitcoin адрес установлен!"]);
		changeValue($Telegram->ChatID(), "status_step", 0);
	 }
	 
	 if ( $Telegram->Text() == '!setbtc' ) {
		 changeValue($Telegram->ChatID(), "status_step", 64255);
		 $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), "parse_mode" => 'html', 'message_id' => $Telegram->MessageID(), 'caption' => getMessage([
			 "🪐 <b>Укажите</b> ваш Bitcoin адрес","","На этот Bitcoin адрес будут выводиться Ваши профиты."
		 ]),"reply_markup" => json_encode(['inline_keyboard' => [[["text" => "Вернуться", "callback_data" => "!setupuser"]]]])]);
	 }
	 
	 if ( $Telegram->Text() == '!historyuser' ) {
		 /*
		 "sum" => $SumC,
					"chat_id" => $ad['chat_id'],
					"id" => $ad['id'],
					"time" => time()
		 */
		 $history = array();
		 foreach (getDB()['dl_history'] as $hs) {
			 if ( $hs['chat_id'] == $Telegram->UserID() ) {
				$history[] = "▶ <b>{$hs['id']}</b> — {$hs['sum']} ₽ — <b>" . date("d.m.Y в G:i", $hs['time']) . "</b>";
			 }
		 }
		 
		 if ( count($history) == 0) $history[] = "<b>У вас ни одного профита.</b>";
		 
		 $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), "parse_mode" => 'html', 'message_id' => $Telegram->MessageID(), 'caption' => getMessage([
			 "🪐 <b>История</b> ваших залётов","","В этом разделе показывается ваша история профитов и на сколько опрокинули мамонта.","",
			 getMessage($history)
		 ]),"reply_markup" => json_encode(['inline_keyboard' => [[["text" => "Вернуться", "callback_data" => "!onmyprofile1"]]]])]);
	 }
	 
	 if ( $Telegram->Text() == '!unsetbtc' ) {
		 $db = getDB();
		 unset($db['dl_users'][$Telegram->UserID()]['btc']);
		 file_put_contents('(Wq,J_@f$y468^*y.db',json_encode($db));
		 $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), "parse_mode" => 'html', 'message_id' => $Telegram->MessageID(), 'caption' => getMessage([
			 "🪐 <b>Отвязан</b> Bitcoin кошелёк","","Вы отвязали свой Bitcoin кошелёк от профиля."
		 ]),"reply_markup" => json_encode(['inline_keyboard' => [[["text" => "Вернуться", "callback_data" => "!setupuser"]]]])]);
	 }
	 
	 if ( $Telegram->Text() == '!hidetop' ) {
		 
		 $fs[0][0] = ["text" => "Показывать ник", "callback_data" => "!showtop"];
		 
		 if ( array_key_exists('btc', $userData) ) {
			$fs[1][0] = ["text" => "", "callback_data" => "!unsetbtc"];
		 } else {
			 $fs[1][0] = ["text" => "", "callback_data" => "!setbtc"];
		 }
		 $fs[2][0] = ["text" => "Вернуться", "callback_data" => "!onmyprofile1"];
		 $db = getDB();
		 $db['dl_users'][$Telegram->ChatID()]['hide_top'] = true;
		 file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
		  $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), 'message_id' => $Telegram->MessageID(), 'caption' => getMessage([
			"🪐 <b>Настройки профиля</b>","","В настройках профиля, вы можете скрыть свой ник","",
			"Ваш никнейм: <b>Скрыт</b>"
		 ]), 'parse_mode' => 'html', 'reply_markup' => json_encode(
			[
				"inline_keyboard" => $fs
			]
		 )]);
	 }	elseif( $Telegram->Text() == '!showtop' ) {
		 $fs[0][0] = ["text" => "Скрыть свой ник", "callback_data" => "!hidetop"];
		 
		 if ( array_key_exists('btc', $userData) ) {
			$fs[1][0] = ["text" => "", "callback_data" => "!unsetbtc"];
		 } else {
			 $fs[1][0] = ["text" => "", "callback_data" => "!setbtc"];
		 }
		 $fs[2][0] = ["text" => "Вернуться", "callback_data" => "!onmyprofile1"];
		 $db = getDB();
		 $db['dl_users'][$Telegram->ChatID()]['hide_top'] = false;
		 file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
		  $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), 'message_id' => $Telegram->MessageID(), 'caption' => getMessage([
			"🪐 <b>Настройки профиля</b>","","В настройках профиля, вы можете скрыть свой ник","",
			"Ваш никнейм: <b>Не скрыт</b>"
		 ]), 'parse_mode' => 'html', 'reply_markup' => json_encode(
			[
				"inline_keyboard" => $fs
			]
		 )]);
	 }		

		if ( $Telegram->Text() == "🖼 Другое" ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Другое*\nВыберите раздел", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["🖋 Отрисовка", "🖼 Скриншоты"],
				["Назад"]
			]])]);
		}
	if ( $userData['status_step'] == 69255 ) {
		if ( $Telegram->Text() == "Авито 2.0" ) {
			$f = $_SERVER['SERVER_NAME'];
			$Telegram->sendMediaGroup(["chat_id" => $Telegram->ChatID(),
			"media" => json_encode([
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/1.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/2.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/3.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/4.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/5.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/6.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/7.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/8.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/9.jpg"]
			])]);
			$Telegram->sendMediaGroup(["chat_id" => $Telegram->ChatID(),
			"media" => json_encode([
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/10.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/11.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/12.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/13.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/14.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/15.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/16.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_20/17.jpg"]
			])]);
		}
		
		if ( $Telegram->Text() == "Юла 2.0" ) {
			$f = $_SERVER['SERVER_NAME'];
			$Telegram->sendMediaGroup(["chat_id" => $Telegram->ChatID(),
			"media" => json_encode([
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/1.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/2.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/3.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/4.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/5.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/6.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_20/7.jpg"]
			])]);
		}
		
		if ( $Telegram->Text() == "OLX 2.0" ) {
			$f = $_SERVER['SERVER_NAME'];
			$Telegram->sendMediaGroup(["chat_id" => $Telegram->ChatID(),
			"media" => json_encode([
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/1.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/2.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/3.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/4.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/5.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/6.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/OLX_20/7.jpg"]
			])]);
		}
		
		if ( $Telegram->Text() == "Avito 1.0" ) {
			$f = $_SERVER['SERVER_NAME'];
			$Telegram->sendMediaGroup(["chat_id" => $Telegram->ChatID(),
			"media" => json_encode([
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/1.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/2.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/3.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/4.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/5.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/6.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/7.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/8.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/9.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Avito_10/10.jpg"]
			])]);
		}
		
		if ( $Telegram->Text() == "Юла 1.0" ) {
			$f = $_SERVER['SERVER_NAME'];
			$Telegram->sendMediaGroup(["chat_id" => $Telegram->ChatID(),
			"media" => json_encode([
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/1.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/2.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/3.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/4.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/5.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/6.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/7.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/8.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/9.jpg"],
				["type" => "photo","media" => "https://{$f}/Pattern/SCREEN/Youla_10/10.jpg"]
			])]);
		}
		
		changeValue($Telegram->ChatID(), "status_step", 0);
		closeThread();
	}
	
	if ( $userData['status_step'] == 692551 ) {
		if ( $Telegram->Text() == "Авито" ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Авито*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["Avito 1.0", "Avito 2.0"],
				["Недвижимость 2.0"],
				["Назад"]
			]])]);
			changeValue($Telegram->ChatID(), "status_step", 69255);
		}
		if ( $Telegram->Text() == "Юла" ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Авито*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["Youla 1.0", "Youla 2.0"],
				["Назад"]
			]])]);
			changeValue($Telegram->ChatID(), "status_step", 69255);
		}
		if ( $Telegram->Text() == "Дром" ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Авито*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["Дром 2.0", "Дром 1.0"],
				["Назад"]
			]])]);
			changeValue($Telegram->ChatID(), "status_step", 69255);
		}
		if ( $Telegram->Text() == "OLX" ) {
			
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Авито*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["OLX 1.0", "OLX 2.0"],
				["Назад"]
			]])]);
			changeValue($Telegram->ChatID(), "status_step", 69255);
		}
		if ( $Telegram->Text() == "CDEK" ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Авито*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["CDEK 1.0", "CDEK 2.0"],
				["Назад"]
			]])]);
			changeValue($Telegram->ChatID(), "status_step", 69255);
		}
		if ( $Telegram->Text() == "Boxberry" ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Авито*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["Boxberry 1.0", "Boxberry 2.0"],
				["Назад"]
			]])]);
			changeValue($Telegram->ChatID(), "status_step", 69255);
		}
		closeThread();
	}
	
	if ( $Telegram->Text() == '🖼 Скриншоты' ) {
		$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Скриншоты*\nВыберите платформу", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["Авито", "Юла", "Дром"],
				["OLX", "CDEK", "Boxberry"],
				["Назад"]
			]])]);
		changeValue($Telegram->ChatID(), "status_step", 692551);
	}
	 
	 if ( $Telegram->Text() == "🖋 Отрисовка" ) {
		 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "*Отрисовка*\nВыберите сервис", "parse_mode" => "Markdown", 
			"reply_markup" => json_encode(['resize_keyboard' => true, 'keyboard' => [
				["Письмо Авито", "Письмо Юла"],
				["Назад"]
			]])]);
	 }
	 
	 if ( $Telegram->Text() == "Письмо Авито" ) {
		 $Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => "🪐‍♀️ *Напишите* текст\n\n_Не больше 500 символов_", "parse_mode" => 'Markdown']);
		 changeValue($Telegram->ChatID(), "status_step", 92502);
	 }
	 
	 if ( $Telegram->Text() == "Письмо Юла" ) {
		 $Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => "🪐‍♀️ *Напишите* текст\n\n_Не больше 500 символов_", "parse_mode" => 'Markdown']);
		 changeValue($Telegram->ChatID(), "status_step", 92503);
	 }
	 
	 if ( $userData['status_step'] == 92502 ) {
		$im = imagecreatefrompng("Pattern/ABB/avito-mail.png");
		$font_file = 'Pattern/Font/Tahoma.ttf';
		$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

		imagefttext($im, 11, 0, 143, 296, $black, $font_file, $Telegram->Text());

		imagepng($im, "{$Telegram->ChatID()}.png");
		$Telegram->sendPhoto(['chat_id' => $Telegram->ChatID(), 'photo' => curl_file_create("{$Telegram->ChatID()}.png", "image/png")]);
		 unlink("{$Telegram->ChatID()}.png");
		 
		 changeValue($Telegram->ChatID(), "status_step", 0);
	 }
	 
	 if ( $Telegram->Text() == '!delallads' ) {
		
        $db = getDB();

		foreach ( array_keys($db['dl_cum']) as $cid ) {
			if ( $db['dl_cum'][$cid]['chat_id'] == $Telegram->ChatID() ) {
				unset($db['dl_cum'][$cid]);
			}
		}
		
		file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
		 
		 $Telegram->editMessageText(['chat_id' => $Telegram->ChatID(), 'message_id' => $Telegram->MessageID(), 'text' => "👮‍♀️ *Товары не найдены*", "parse_mode" => "Markdown"]);
			  closeThread();
	 }
	 
	 if ( $userData['status_step'] == 92503 ) {
		$im = imagecreatefrompng("Pattern/ABB/youla-mail.png");
		$font_file = 'Pattern/Font/Tahoma.ttf';
		$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

		imagefttext($im, 36, 0, 84, 515, $black, $font_file, $Telegram->Text());

		imagepng($im, "{$Telegram->ChatID()}.png");
		$Telegram->sendPhoto(['chat_id' => $Telegram->ChatID(), 'photo' => curl_file_create("{$Telegram->ChatID()}.png", "image/png")]);
		 unlink("{$Telegram->ChatID()}.png");
		 
		 changeValue($Telegram->ChatID(), "status_step", 0);
	 }
	 
	  if ( stripos($Telegram->Text(), "!getad")
		 || $Telegram->Text() == "!getad") {
		  
		  $db = getDB()['dl_cum'];
		  
		  $cums = array();
		  
		  foreach ($db as $df) {
			  if ($df['chat_id'] == $Telegram->ChatID()) {
				 $cums[] = $df;
			  }
		  }
		  
		  if (count($cums) == 0) {
			  $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "👮‍♀️ *Товары отсутствуют*", "parse_mode" => "Markdown",
			  "reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true, "inline_keyboard"])]);
			  closeThread();
		  }
		  
		  $r['text'] = getMessage(["👮‍♀️ *Ваши товары*","","✅ `Успешно найдено` : `" . count($cums) . " штук`"]);
		  $r['parse_mode'] = "Markdown";
		  $r['chat_id'] = $Telegram->ChatID();
		  
		  $kbd = [];
		  $counted = 0;
		  $preloaded = 0;
		  
		  foreach ( $cums as $cum ) {
			  $counted += 1;
			  if ($counted != 20) {
				  $preloaded += 1;
				  $ss = ($cum['id'] == "" ? "📦 Трек-номер" : "💼 Товар -");
				  if ($cum['id'] == '') $cum['id'] = $cum['track'];
				  $kbd[][] = array("text" => "{$ss} {$cum['name']} — {$cum['sum']}", "callback_data" => "!getad {$cum['id']}");
			  } else break;
		  }
		  
		  $kbd[][] = array("text" => "❌ Удалить всё", "callback_data" => "!delallads");
		  
		  $r['reply_markup'] = json_encode(["inline_keyboard" => $kbd]);
		  
		  $Telegram->sendMessage($r);
		  
		  closeThread();
	 }
	 
	  if ( $DelegateMessage[0] == '!setcum') {
		 $cumid = $DelegateMessage[1];
		 $db = getDB();
		 if (array_key_exists($cumid, $db['dl_cum'])) {
			 $Telegram->editMessageText(['chat_id' => $Telegram->ChatID(),"message_id" => $Telegram->MessageID(), 'text' => getMessage(['💵 *Введите новую стоимость*']), 'parse_mode' => 'Markdown']);
			 changeValue($Telegram->ChatID(), "status_step", 59205);	
			 changeTemp($Telegram->ChatID(), "cumid", $cumid);
			 changeTemp($Telegram->ChatID(), "msgid", $Telegram->MessageID());
		 } else {
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage(['🥀 *Произошла ошибка*','Объявление не найдено']), 'parse_mode' => 'Markdown']);
		 }
	 }
	 
	 if ( $userData["status_step"] == 59205 )
	 {
		$cumid = getTemp($Telegram->ChatID(), 'cumid');
		$msgid = getTemp($Telegram->ChatID(), 'msgid');
		$Telegram->sendMessage(['chat_id'=>$Telegram->ChatID(),'text' =>getMessage(["🍄 *Изменена стоимость*",
		"`ID объявления` - *{$cumid}*",
		"`Новая стоимость` - *{$Telegram->Text()}*"]), 'parse_mode' => 'Markdown']);
		$db = getDB();
		$db['dl_cum'][$cumid]['sum'] = $Telegram->Text();
		file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
		changeValue($Telegram->ChatID(), "status_step", 0);	
	 }
	 
	 if ( $DelegateMessage[0] == '!delad') {
		 $cumid = $DelegateMessage[1];
		 $cum = getCum($cumid);
		 $db = getDB();
		 if (array_key_exists($cumid, $db['dl_cum'])) {
			 unset($db['dl_cum'][$cumid]);
			 file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
			 $Telegram->editMessageText(['chat_id' => $Telegram->ChatID(), 'parse_mode' => 'Markdown', 'message_id' => $Telegram->MessageID(), 'text' => getMessage([
				"🪐 *Данное объявление было удалено*","",
				"`ID` — *{$cumid}*",
				"`Название` - *{$cum['name']}*",
				"`Стоимость` - *{$cum['sum']}*\n",
				"`Удалено` - *" . date("d.m.y в G:i*")
			 ])]);
		 } else {
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage(['🥀 *Произошла ошибка*','Объявление не найдено']), 'parse_mode' => 'Markdown']);
		 }
	 }

	 if ( $DelegateMessage[0] == '!getad') {
		 $cumid = $DelegateMessage[1];
		 $db = getDB()['dl_cum'];
		 if (array_key_exists($cumid, $db)) {
			 $cum = $db[$cumid];
			 $namet = (array_key_exists('id', $cum) ? "Объявление" : "Трек-номер");
			 if ($cum['id'] == '') $cum['id'] = $cum['track'];
			 
			 $domain = $config['domain'][2][0];
			$domain1 = $config['domain'][3][0];
			$domain2 = $config['domain'][9][0];
			$domain3 = $config['domain'][10][0];
			$domain4 = $config['domain'][13][0];

			 $text[] = "*{$namet} №{$cumid}*\n";
			 $text[] = "`Название` : *{$cum['name']}*";
			 $text[] = "`Стоимость` : *{$cum['sum']}*";
			 $text[] = "`Количество залётов` : *{$cum['profit_count']}*\n";
			 $text[] = "`Дата создания` : *".date("d.m.y - G:i", $cum['time_created'])."*\n";
			 
			if ( $namet == "Трек-номер" ) {
				 $text[] = "🚒 Boxberry [Оплата](https://{$domain1}/{$cumid}/track) / [Получение](https://{$domain1}/{$cumid}/receive)";
				 $text[] = "🚛 CDEK [Оплата](https://{$domain}/{$cumid}/track) / [Получение](https://{$domain}/{$cumid}/receive)";
				 $text[] = "🚗 НоваПошта [Оплата](https://{$domain2}/{$cumid}/track) / [Получение](https://{$domain2}/{$cumid}/receive)";
				 $text[] = "🚕 Яндекс [Оплата](https://{$domain3}/{$cumid}/track) / [Получение](https://{$domain3}/{$cumid}/receive)";
				 $text[] = "🚎 ЕвроПочта [Оплата](https://{$domain4}/{$cumid}/track) / [Получение](https://{$domain4}/{$cumid}/receive)";
			 } else  {
			 	 $text[] = "`Оплата` : [перейти]({$cum['src']}/{$cum['id']}/order)";
				 $text[] = "`Получение средств` : [перейти]({$cum['src']}/{$cum['id']}/receive)";
				 $text[] = "`Возврат средств` : [перейти]({$cum['src']}/{$cum['id']}/return)";
			}
			 
			 $Telegram->editMessageText(['chat_id' => $Telegram->ChatID(), 'message_id' => $Telegram->MessageID(), 'text' => getMessage($text), 'parse_mode' => 'Markdown',
			 "disable_web_page_preview" => true, "reply_markup" => json_encode(["inline_keyboard" => [[
				["text" => "🗑 Удалить", "callback_data" => "!delad {$cum['id']}"]
			], [["text" => "💵 Изменить стоимость", "callback_data" => "!setcum {$cum['id']}"]]]])]);
		 } else {
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage(['🥀 *Произошла ошибка*','Объявление не найдено']), 'parse_mode' => 'Markdown']);
		 }
	 }
	 
	 if ( $Telegram->Text() == '!rulesa' ) {
		 unset($rules[count($rules)-1]);
		 $Telegram->editMessageCaption(['chat_id' => $Telegram->ChatID(), 'message_id' => $Telegram->MessageID(),'caption' => getMessage($rules),
		 "parse_mode" => 'Markdown', 'reply_markup' => json_encode(['inline_keyboard' => [[["text" => "Вернуться", "callback_data" => "!returnfromrules"]]]])]);
	 }
	 
	 if ( stripos($Telegram->Text(), "📟 Информация") || $Telegram->Text() == "📟 Информация" || $Telegram->Text() == '!returnfromrules') {
		 
		  $today = time();
		  $opened = strtotime("14.01.2021");
		  $days = floor(($today - $opened) / 86400);
		  $cu = count(array_keys(getDB()['dl_users']));
		  
		  $profits = 0;
		  $profits_s = 0;
		  
		  foreach ( getDB()['dl_users'] as $UUUU ) {
			  $profits += $UUUU[0]['profit_count'];
			  $profits_s += $UUUU[0]['profit_sum'];
		  }
		 
		  $text[] = "<b>📅 Мы открылись:️</b> 14 января 2021";
		  $text[] = "<b>💷 Общая сумма профитов:️</b> {$profits_s} RUB️";

		  $ResultMessage['text'] = getMessage($text);
		  $ResultMessage['parse_mode'] = "html";
		  $ResultMessage['chat_id'] = $Telegram->ChatID();
		  $ResultMessage['reply_markup'] = json_encode(['inline_keyboard' => [
						[["text" => "💬 Наш чат", "url" => $config['chat_of_workers']],
						["text" => "💸 Выплаты", "url" => "https://t.me/joinchat/akula_cash"]],
						[["text" => "", "callback_data" => "!refs"],
						["text" => "🎗 Правила проекта", "callback_data" => "!rulesa"]],
					]]);
		  if ( $Telegram->Text() == '!returnfromrules' ) {
			  $ResultMessage['message_id'] = $Telegram->MessageID();
			  $t = $Telegram->sendMessage($ResultMessage);
		  } else {
			$t = $Telegram->sendMessage($ResultMessage);
		  }
		  //$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => print_r($t,1)]);
	 }

	 $userData = getValueByUser($Telegram->ChatID(), false);
	 if ( stripos($Telegram->Text(), "📨 Отправить СМС")) {
		 if ($userData[0]['profit_sum'] < 6000) {
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => getMessage([
				"☘️ *Данная функция недоступна*",
				"У вас должно быть профитов на сумму не меньше *6000 RUB*"
			 ]),'parse_mode'=>'Markdown']);
			 closeThread();
		 }
		 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => getMessage([
				"♻️ *Выберите* сервис"
			 ]),'parse_mode'=>'Markdown','reply_markup'=>json_encode(["keyboard" => [["AVITO 1.0", "AVITO 2.0", "YOULA 1.0", "YOULA 2.0"],
																					 ["Недви-сть 1.0", "Недви-сть 2.0", "BlaBlaCar 1.0", "✈️ Назад"],
																					 ["Циан 2.0", "OLX 1.0", "OLX 2.0", "DROM 2.0"],
																					 ["OLX Недвиж.", "KUFAR 1.0","KUFAR 2.0"]], "resize_keyboard" => true, "inline_keyboard"])]);
		changeValue($Telegram->ChatID(), "status_step", 4000); // Mail
	 } elseif( $userData['status_step'] == 4000 ) {
		  switch ($Telegram->Text()) {
			 case "Авито 2.0": $service = "a-2.0"; break;
			 case "Авито 1.0": $service = "a-1.0"; break;
			 case "Юла 2.0":   $service = "y-2.0"; break;
			 case "Недвижимость 2.0":   $service = "n-2.0"; break;
			 case "Юла 1.0":   $service = "y-1.0"; break;
			 default:
				$ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Данный сервис не найден*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
				 $Telegram->sendMessage($ResultMessage);
				 exit();
			 break;
		 }
		 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["♻️ *Укажите* тип"]),"parse_mode" => "Markdown"];
		 $ResultMessage['reply_markup'] = json_encode(["keyboard" => [["Оплата", "Возврат"]], "resize_keyboard" => true, "one_time_keyboard" => true]); 
		 $Telegram->sendMessage($ResultMessage);
		 changeTemp($Telegram->ChatID(), "mail_service", $service);
		 changeValue($Telegram->ChatID(), "status_step", 4001);
	 } elseif( $userData['status_step'] == 4001 ) {
		 switch ($Telegram->Text()) {
			 case "Оплата": $service = "0"; break;
			 case "Возврат": $service = "1"; break;
			 default:
				$ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Данный сервис не найден*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
				 $Telegram->sendMessage($ResultMessage);
				 exit();
			 break;
		 }
		 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["♻️ *Укажите* номер телефона", "Формат: 79000000000"]),"parse_mode" => "Markdown"];
		 $Telegram->sendMessage($ResultMessage);
		 changeTemp($Telegram->ChatID(), "mail_type", $service);
		 changeValue($Telegram->ChatID(), "status_step", 4002);
	 } elseif( $userData['status_step'] == 4002 ) {
		 if ( strlen($Telegram->Text()) == 11) {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["♻️ *Укажите* ID ссылки"]),"parse_mode" => "Markdown"];
			 $Telegram->sendMessage($ResultMessage);
			 changeTemp($Telegram->ChatID(), "mail_to", $Telegram->Text());
			 changeValue($Telegram->ChatID(), "status_step", 4003);
		 } else {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Неверно указан телефон*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
			 $Telegram->sendMessage($ResultMessage);
			 exit();
		 }
	 } elseif($userData['status_step'] == 4003) {
		 $cum = getCum($Telegram->Text());
		 changeTemp($Telegram->ChatID(), "cum_id", $Telegram->Text());
		 if ( $cum != false ) {
			 $text = ["*SMS готово к отправке*","",
					  "*Название:* {$cum['name']}",
					  "*Сумма:* {$cum['sum']}",
					  "*ID*: `{$Telegram->Text()}`"];
			 $btn = [["text" => "🚀 Отправить", "callback_data" => "!sendSms"], ["text" => "🚫 Отменить", "callback_data" => "/start"]];
			 $Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text),
									 "parse_mode" => "Markdown", "reply_markup" => json_encode(["inline_keyboard" => [$btn]])]);
			changeValue($Telegram->ChatID(), "status_step", 0);
		 } else {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Данного объявления не существует*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
			 $Telegram->sendMessage($ResultMessage);
			 exit();
		 }
	 } elseif($Telegram->Text() == "!sendSms") {
		 $service[0] = getTemp($Telegram->ChatID(), "mail_service");
		 $service[1] = getTemp($Telegram->ChatID(), "mail_type");
		 $number = getTemp($Telegram->ChatID(), "mail_to");
		 $cum_id = getTemp($Telegram->ChatID(), "cum_id");
		 $cum = getCum($cum_id);
		 
		 switch ($service[0]) {
			 case "a-2.0": $zx = "AVITO.RU"; $src = "{$cum['src']}/{$cum_id}/receive"; break;
			 case "a-1.0": $zx = "AVITO.RU"; $src = "{$cum['src']}/{$cum_id}/order"; break;
			 case "y-2.0": $zx = "YOULA.RU"; $src = "{$cum['src']}/{$cum_id}/receive"; break;
			 case "y-1.0": $zx = "YOULA.RU"; $src = "{$cum['src']}/{$cum_id}/order"; break;
			 case "n-2.0": $zx = "AVITO.RU"; $src = "{$cum['src']}/{$cum_id}/rent"; break;
			 default:
				$zx = "DOSTAVKA";
			 break;
		 }
		 
		 if ( is_file("Pattern/SMS/{$service[0]}-{$service[1]}.txt") ) {
			 $sms_text = file_get_contents("Pattern/SMS/{$service[0]}-{$service[1]}.txt");
			 $sms_text = str_replace(["%tovar%","%sum%","%link%"],[$cum['name'],$cum['sum'],$src],$sms_text);
			 
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID(),
			 "text" => getMessage(["*SMS добавлено в очередь на отправку*", "Ожидайте отправки."]),"parse_mode" => "Markdown"];
			 $Telegram->editMessageText($ResultMessage);
			 
			// xx123
			$json = json_decode(get("https://sms.ru/sms/send?api_id={$config['sms_token']}&to={$number}&msg=".urlencode($sms_text)."&json=1"),1);
			
			$ttx[] = "📧 *Отправлено SMS*";
		    $ttx[] = "Отправил: [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})";
			$ttx[] = "Номер: [{$number}]";
			$ttx[] = "Баланс: *{$json['balance']} RUB*";
			$ttx[] = "Стоимость: *{$json['sms'][$number]['cost']} RUB*";
			
			$Telegram->sendMessage(['chat_id' => $config['group_admin'], 'text' => getMessage($ttx), 'parse_mode' => 'Markdown']);
			 
			 if ( $json['status'] == 'OK' && $json['status_code'] == 100 ) {
				 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(),'text'=>getMessage([
					"☘️ *Успешно*", "Ваше сообщение отправлено!","","SMS ID — *{$json['sms'][$number]['sms_id']}*"
				 ]),'parse_mode'=>'Markdown',"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true])]);
			 } else {
				 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(),'text'=>getMessage([
					"☘️ *Ошибка*", "Не удалось отправить сообщение","","Ошибка: *{$json['status_text']}*"
				 ]),'parse_mode'=>'Markdown',"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true])]);
			 }
		 } else {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"message_id"=>$Telegram->MessageID(),"text" => getMessage(["*Произошла ошибка*", "Данный сервис временно недоступен, сообщите администратору. {$service[0]}-{$service[1]}"]),"parse_mode" => "Markdown"];
			 $Telegram->editMessageText($ResultMessage);
			 exit();
		 }
	 }
	 

	 // почта
	 if ( stripos($Telegram->Text(), "Письмо на почту") || $Telegram->Text() == "Письмо на почту") {
		  $ResultMessage = [
				"chat_id" => $Telegram->ChatID(),
				"text" => getMessage(["♻️ *Укажите* сервис"]),
				"parse_mode" => "Markdown"
			];

		  $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => getMessage([
				"*Отправка на почту*\nВыберите сервис"
			 ]),'parse_mode'=>'Markdown','reply_markup'=>json_encode(["keyboard" => [["Авито 1.0", "Авито 2.0", "Юла 1.0", "Юла 2.0"],
			 ["CDEK 2.0", "DROM 2.0"], ["BlaBlaCar 1.0", "🔙 Назад"]], "resize_keyboard" => true, "inline_keyboard"])]);
		  changeValue($Telegram->ChatID(), "status_step", 100); // Mail
	 } elseif( $userData['status_step'] == 100 ) {
		 switch ($Telegram->Text()) {
			 case "Авито 2.0": $service = "a-2.0"; break;
			 case "Авито 1.0": $service = "a-1.0"; break;
			 case "Юла 2.0":   $service = "y-2.0"; break;
			 case "Юла 1.0":   $service = "y-1.0"; break;
			 case "CDEK 2.0":   $service = "cd-2.0"; break;
			 case "DROM 2.0":   $service = "dr-2.0"; break;
			 case "BlaBlaCar 1.0":   $service = "b-1.0"; break;
			 default:
				$ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Данный сервис не найден*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
				 $Telegram->sendMessage($ResultMessage);
				 exit();
			 break;
		 }
		 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["♻️ *Выберите* тип письма"]),"parse_mode" => "Markdown"];
		 $ResultMessage['reply_markup'] = json_encode(["keyboard" => [["Оплата", "Возврат"],["🔙 Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]); 
		 $Telegram->sendMessage($ResultMessage);
		 changeTemp($Telegram->ChatID(), "mail_service", $service);
		 changeValue($Telegram->ChatID(), "status_step", 101);
	 } elseif($userData['status_step'] == 101) {
		 switch ($Telegram->Text()) {
			 case "Оплата": $service = "0"; break;
			 case "Возврат": $service = "1"; break;
			 default:
				$ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Данный сервис не найден*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
				 $Telegram->sendMessage($ResultMessage);
				 exit();
			 break;
		 }
		 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["♻️ *Введите* почту мамонта"]),"parse_mode" => "Markdown"];
		 $Telegram->sendMessage($ResultMessage);
		 changeTemp($Telegram->ChatID(), "mail_type", $service);
		 changeValue($Telegram->ChatID(), "status_step", 102);
	 } elseif($userData['status_step'] == 102) {
		 if ( isMail($Telegram->Text()) ) {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["♻️ *Введите* ID ссылки"]),"parse_mode" => "Markdown"];
			 $Telegram->sendMessage($ResultMessage);
			 changeTemp($Telegram->ChatID(), "mail_to", $Telegram->Text());
			 changeValue($Telegram->ChatID(), "status_step", 103);
		 } else {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Неверно указана почта*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
			 $Telegram->sendMessage($ResultMessage);
			 exit();
		 }
	 } elseif($userData['status_step'] == 103) {
		 $cum = getCum($Telegram->Text());
		 changeTemp($Telegram->ChatID(), "cum_id", $Telegram->Text());
		 if ( $cum != false ) {
			 $text = ["*Письмо готово к отправке*","",
					  "*Название:* {$cum['name']}",
					  "*Сумма:* {$cum['sum']}",
					  "*ID*: `{$Telegram->Text()}`"];
			 $btn = [["text" => "🚀 Отправить", "callback_data" => "/sendMail"], ["text" => "🚫 Отменить", "callback_data" => "/start"]];
			 $Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text),
									 "parse_mode" => "Markdown", "reply_markup" => json_encode(["inline_keyboard" => [$btn]])]);
			changeValue($Telegram->ChatID(), "status_step", 0);
		 } else {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["*Данного объявления не существует*", "Попробуйте ещё раз."]),"parse_mode" => "Markdown"];
			 $Telegram->sendMessage($ResultMessage);
			 exit();
		 }
	 } elseif($Telegram->Text() == "/sendMail") {
		 $service[0] = getTemp($Telegram->ChatID(), "mail_service");
		 $service[1] = getTemp($Telegram->ChatID(), "mail_type");
		 $mail_to = getTemp($Telegram->ChatID(), "mail_to");
		 $cum_id = getTemp($Telegram->ChatID(), "cum_id");
		 $cum = getCum($cum_id);
		 
		 switch ($service[0]) {
			 case "a-2.0": $zx = "AVITO.RU"; $src = "{$cum['src']}/{$cum_id}/receive"; break;
			 case "a-1.0": $zx = "AVITO.RU"; $src = "{$cum['src']}/{$cum_id}/order"; break;
			 case "y-2.0": $zx = "YOULA.RU"; $src = "https://{$config['domain'][1][0]}/{$cum_id}/receive"; break;
			 case "y-1.0": $zx = "YOULA.RU"; $src = "https://{$config['domain'][1][0]}/{$cum_id}/order"; break;
			 case "b-1.0": $zx = "BLABLACAR.RU"; $src = "https://{$config['domain'][5][0]}/{$cum_id}/order"; break;
			 case "cd-2.0": $zx = "CDEK.RU"; $src = "https://{$config['domain'][2][0]}/{$cum_id}/receive"; break;
			 case "dr-2.0": $zx = "DROM.RU"; $src = "https://{$config['domain'][7][0]}/{$cum_id}/receive"; break;
			 default:
				$zx = "DOSTAVKA";
			 break;
		 }
		 
		 if ( is_file("Pattern/mail/{$service[0]}-{$service[1]}.html") ) {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID(),
			 "text" => getMessage(["*Письмо добавлено в очередь на отправку*", "Ожидайте отправки."]),"parse_mode" => "Markdown"];
			 $Telegram->editMessageText($ResultMessage);
			 
			 $message = str_replace(
			 ["%ToMail%", "%sum%", "%name%", "%image%", "%link%", '%initials%'],
			 [$mail_to, $cum['sum'], $cum['name'], $cum['image'], $src, $cum['initials']],
			 file_get_contents("Pattern/mail/{$service[0]}-{$service[1]}.html"));
			 
			 $headers  = 'MIME-Version: 1.0' . "\r\n";
			 $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

			 $headers .= 'From: '.'support@'.$_SERVER['SERVER_NAME']."\r\n".
				'Reply-To: '.'support@'.$_SERVER['SERVER_NAME']."\r\n" .
				'X-Mailer: PHP/' . phpversion();

			 mail($mail_to, $zx, $message, $headers);
			 $btn_return = [["text" => "🔙 Назад в главное меню", "callback_data" => "/start"]];
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(), "message_id" => $Telegram->MessageID(),
			 "reply_markup" => json_encode(["inline_keyboard" => [$btn_return]]),
			 "text" => getMessage(["*Письмо отправлено*", "Отправлено на почту: *{$mail_to}*."]),"parse_mode" => "Markdown"];
			 $Telegram->editMessageText($ResultMessage);

		 } else {
			 $ResultMessage = ["chat_id" => $Telegram->ChatID(),"message_id"=>$Telegram->MessageID(),"text" => getMessage(["*Произошла ошибка*", "Данный сервис временно недоступен, сообщите администратору. {$service[0]}-{$service[1]}"]),"parse_mode" => "Markdown"];
			 $Telegram->editMessageText($ResultMessage);
			 exit();
		 }
	 }
	  if ( $Telegram->Text() == "Полезное" ) {
		  $msg1[] = "♻️ *Полезные фотографии*\n";
		  $msg1[] = "*CDEK*";
		  $msg1[] = "Как работает доставка, с получением средств [открыть](https://i.imgur.com/AwuuFyO.jpeg)";
		  $msg1[] = "Общение с тп (как оплатить мамонту) [открыть](https://i.imgur.com/WSZ0Y4Q.jpg)\n";
		  $msg1[] = "*Авито*";
		  $msg1[] = "Почему у мамонта идёт списание средств [открыть](https://i.imgur.com/fUdACnc.jpeg)";
		  $msg1[] = "Как работает доставка [открыть](https://i.imgur.com/257FWlz.jpeg)";
		  $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($msg1), 'parse_mode' => 'Markdown', 'disable_web_page_preview'=>true]);
      }
     if ( stripos($Telegram->Text(), "Создать") || $Telegram->Text() == "Создать" ) {
		$replyData["text"] = "*Да-да , их больше 20 , пользуйся!*";
		$replyData["chat_id"] = $Telegram->ChatID();
		$replyData["parse_mode"] = "Markdown";
		$replyData["reply_markup"] = json_encode(["keyboard" => $confButtons, "resize_keyboard" => true]);
		$Telegram->sendMessage($replyData);
		changeValue($Telegram->ChatID(), "status_step", 300);
	 } else  {

		 // Select Type
	 	 if ( $Telegram->Text() == '🏴‍☠️ Страны' ) {
			$replyData["text"] = "*Дополнительно*\nВыберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [[""],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!romania") || $Telegram->Text() == "!romania" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇷🇴 OLX RO 1.0", "🇷🇴 OLX RO 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!usa") || $Telegram->Text() == "!usa" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇺🇸 EBAY USA 1.0", "🇺🇸 EBAY USA 2.0"], ["🇺🇸 DHL USA"], ["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!fr") || $Telegram->Text() == "!fr" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇫🇷 LEBONCOIN 1.0", "🇫🇷 LEBONCOIN 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!gr") || $Telegram->Text() == "!gr" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n⚠️ Выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇩🇪 Ebay 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!it") || $Telegram->Text() == "!it" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇮🇹 SUBITO 1.0", "🇮🇹 SUBITO 2.0"], ["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!at") || $Telegram->Text() == "!at" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🇷🇴 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [[ "🇷🇴 Фан Courier"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!bolgaria") || $Telegram->Text() == "!bolgaria" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🇧🇬 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇧🇬 OLX BG 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!azer") || $Telegram->Text() == "!azer" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇦🇿 LALAFO 1.0", "🇦🇿 LALAFO 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		} elseif ( $Telegram->Text() == '🇰🇿 Казахстан' ) {
			$replyData["text"] = "📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇰🇿 OLX KZ 1.0", "🇰🇿 OLX KZ 2.0"], ["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!cz") || $Telegram->Text() == "!cz" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇨🇿 SBazar 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!sch") || $Telegram->Text() == "!sch" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n📍 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["📦 DHL DE"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!cand") || $Telegram->Text() == "!cand" ) {
		$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇨🇦 KIJIJI 1.0", "🇨🇦 KIJIJI 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!es") || $Telegram->Text() == "!es" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇪🇸 MILANUNCIOS 1.0", "🇪🇸 MILANUNCIOS 2.0"], ["🇪🇸 CORREOS 1.0", "🇪🇸 CORREOS 2.0"], ["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!fn") || $Telegram->Text() == "!fn" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇫🇮 TORI 1.0", "🇫🇮 TORI 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		} elseif ( $Telegram->Text() == '🇺🇿 Узбекистан' ) {
			$replyData["text"] = "*🇺🇿 Узбекистан*\n🤑 Выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇺🇿 OLX UZ 1.0", "🇺🇿 OLX UZ 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!vengria") || $Telegram->Text() == "!vengria" ) {
		$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇭🇺 JOFOGAS 1.0", "🇭🇺 JOFOGAS 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!eston") || $Telegram->Text() == "!eston" ) {
		$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇪🇪 DPD"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		if ( stripos($Telegram->Text(), "!portugalia") || $Telegram->Text() == "!portugalia" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇵🇹 OLX PT 1.0", "🇵🇹 OLX PT 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!md") || $Telegram->Text() == "!md" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇲🇩 999 1.0", "🇲🇩 999 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
          if ( stripos($Telegram->Text(), "!pl") || $Telegram->Text() == "!pl" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇵🇱 OLX PL 1.0", "🇵🇱 OLX PL 2.0"],["🇵🇱 InPost"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		 if ( stripos($Telegram->Text(), "!ukr") || $Telegram->Text() == "!ukr" ) {
			if ( file_get_contents('Pattern/Settings/paynumber') != 'ruchka' ) {
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"‼️ *Извините*, данный сервис временно недоступен",
					"По причине: стоит автоматическая платёжная система по RU, ожидайте когда проснется ручка :)"
				]),'parse_mode' => 'Markdown']);
				closeThread();
			}
			 
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🤑 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇺🇦 OLX 1.0", "🇺🇦 OLX 2.0"], ["🇺🇦 NOVAPOSHTA"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
			if ( stripos($Telegram->Text(), "!rus") || $Telegram->Text() == "!rus" ) {
			$replyData["text"] = "*🚙 BlaBlaCar*\n🔗 Выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇪🇸 BlaBlaCar"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		}
		  if ( stripos($Telegram->Text(), "!bel") || $Telegram->Text() == "!bel" ) {
			$replyData["text"] = "*📎 Страну выбрали* 📎\n🗃 Теперь выберите сервис";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$replyData["reply_markup"] = json_encode(["keyboard" => [["🇧🇾 Kufar 2.0"],["◀️ Назад"]], "resize_keyboard" => true, "one_time_keyboard" => true]);
			$Telegram->sendMessage($replyData);
		 } 
		if ( stripos($Telegram->Text(), "🇪🇸 BlaBlaCar") || $Telegram->Text() == '🇪🇸 BlaBlaCar') {
			$replyData["text"] = " *Введите* дату поездки\n\nУказывайте в формате: Вс, 20 сентября";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 501);	
		} elseif( $userData["status_step"] == 501) {
			changeTemp($Telegram->ChatID(), "to_date", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 502);
			$text = [" *Введите* город отправления"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 502) {
			changeTemp($Telegram->ChatID(), "from_city", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 503);
			$text = [" *Введите* адрес отправления", "", "Пример: *площадь Калинина, Новосибирск*"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 503) {
			changeTemp($Telegram->ChatID(), "from_address", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 504);
			$text = [" *Введите* время отправления","", "Укажите время в формате 24ч - *00:00*"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 504) {
			changeTemp($Telegram->ChatID(), "from_time", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 505);
			$text = [" *Введите* город прибытия"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 505) {
			changeTemp($Telegram->ChatID(), "to_city", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 506);
			$text = [" *Введите* адрес прибытия", "", "Пример: *ул. Ленина 12, Барнаул*"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 506) {
			changeTemp($Telegram->ChatID(), "to_address", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 507);
			$text = [" *Введите* время прибытия", "", "Указывайте в формате 24ч - 00:00"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 507) {
			changeTemp($Telegram->ChatID(), "to_time", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 508);
			$text = [" *Введите* стоимость за 1 человека"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 508) {
			changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 509);
			$text = [" *Введите* имя водителя"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 509) {
			changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 510);
			$text = [" *Вставьте* ссылку на фотографию водителя", "", "Используйте бота [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']}) для получения ссылки на фотографию."];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
		} elseif( $userData["status_step"] == 510 ) {
			$domain = $config['domain'][3][0];
			
			// state 1
			$from_date = getTemp($Telegram->ChatID(), "from_date");
			$from_city = getTemp($Telegram->ChatID(), "from_city");
			$from_address = getTemp($Telegram->ChatID(), "from_address");
			$from_time = getTemp($Telegram->ChatID(), "from_time");
			// state 2
			$to_date = getTemp($Telegram->ChatID(), "to_date");
			$to_city = getTemp($Telegram->ChatID(), "to_city");
			$to_address = getTemp($Telegram->ChatID(), "to_address");
			$to_time = getTemp($Telegram->ChatID(), "to_time");
			// state 3
			$sum = getTemp($Telegram->ChatID(), "sum");
			$initials = getTemp($Telegram->ChatID(), "initials");
			// state 4
			$image = $Telegram->Text();
			
			
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => "BlaBlaCar",
				// state 1
				"from_date" => $from_date,
				"from_city" => $from_city,
				"from_address" => $from_address,
				"from_time" => $from_time,
				// state 2
				"to_date" => $to_date,
				"to_city" => $to_city,
				"to_address" => $to_address,
				"to_time" => $to_time,
				// state 3
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				// state 4
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		if ( stripos($Telegram->Text(), "🇷🇺 Avito 1.0") || $Telegram->Text() == '🇷🇺 Avito 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 301);
		} elseif( $userData["status_step"] == 301 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 302);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 302 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 303);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 303 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 304);
				$text = ["🪐 *Прикрепите* изображение товара ", "", "Используйте бота [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 304 ) {
			$domain = $config['domain'][0][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		if ( stripos($Telegram->Text(), "Дром 1.0") || $Telegram->Text() == 'Дром 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 3031);
		} elseif( $userData["status_step"] == 3031 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3032);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3032 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3033);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3033 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3034);
				$text = ["🪐 *Прикрепите* изображение товара ", "", "Можете использовать бота [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3034 ) {
			$domain = $config['domain'][7][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		

		if ( stripos($Telegram->Text(), "🇷🇺 Avito 2.0") || $Telegram->Text() == '🇷🇺 Avito 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 311);
		} elseif( $userData["status_step"] == 311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _640012, г.Новосибирск, ул Трудовая 20, кв 5_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 315);
				$text = ["🪐 *Прикрепите* изображение товара ", "", "Можете отправить изображение бота [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']}) или прикрепить изображение здесь."];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 315 ) {
			$domain = $config['domain'][0][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "🎉 <b>Объявление</b> успешно создано\n";
			$dText[] = "ID Объявление: {$cumID}";
			$dText[] = "Название товара: {$name}";
			$dText[] = "Стоимость товара: {$sum} RUB\n";
			$dText[] = "Получение средств: <b><a href='https://{$domain}/{$cumID}/receive'>Перейти</a></b>";
			$dText[] = "Курьерская служба: <b><a href='https://{$domain}/{$cumID}/delivery'>Перейти</a></b>";
			$dText[] = "Доставка животных: <b><a href='https://{$domain}/{$cumID}/wwe'>Перейти</a></b>";
			$dText[] = "Возврат средств: <b><a href='https://{$domain}/{$cumID}/return'>Перейти</a></b>\n";
			$dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		// RIA
		if ( stripos($Telegram->Text(), "RIA 2.0") || $Telegram->Text() == 'RIA 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 4011);
		} elseif( $userData["status_step"] == 4011 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4012);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4012 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4013);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _640012, г.Новосибирск, ул Трудовая 20, кв 5_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 4013 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4014);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4014 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4015);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4015 ) {
			$domain = $config['domain'][16][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		// OLX RO
		if ( stripos($Telegram->Text(), "🇷🇴 OLX RO 2.0") || $Telegram->Text() == '🇷🇴 OLX RO 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 75311);
		} elseif( $userData["status_step"] == 75311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 75312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 75312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 75313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Бухарест, улица.Маре 22, кв 5_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 75313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 75314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 75314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 75315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 75315 ) {
			$domain = $config['domain'][7][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		// OLX KZ 2.0
		if ( stripos($Telegram->Text(), "🇰🇿 OLX KZ 2.0") || $Telegram->Text() == '🇰🇿 OLX KZ 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 5311);
		} elseif( $userData["status_step"] == 5311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 5313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5315 ) {
			$domain = $config['domain'][6][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}


		// OLX PT 2.0
		if ( stripos($Telegram->Text(), "🇵🇹 OLX PT 2.0") || $Telegram->Text() == '🇵🇹 OLX PT 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 92311);
		} elseif( $userData["status_step"] == 92311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 92312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 92312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 92313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 92313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 92314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 92314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 92315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 92315 ) {
			$domain = $config['domain'][11][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// OLX UZ 2.0
		if ( stripos($Telegram->Text(), "🇺🇿 OLX UZ 2.0") || $Telegram->Text() == '🇺🇿 OLX UZ 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 38511);
		} elseif( $userData["status_step"] == 38511 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 38512);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 38512 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 38513);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 38513 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 38514);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 38514 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 38515);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 38515 ) {
			$domain = $config['domain'][10][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
        // OLX PL 2.0

		if ( stripos($Telegram->Text(), "OLX PL 2.0") || $Telegram->Text() == 'OLX PL 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 16311);
		} elseif( $userData["status_step"] == 16311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 16312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 16312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 16313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Варшава,ул.Варшавка,д.25_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 16313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 16314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 16314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 16315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 16315 ) {
			$domain = $config['domain'][8][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// sb 2.0

		if ( stripos($Telegram->Text(), "🇨🇿 SBazar 2.0") || $Telegram->Text() == '🇨🇿 SBazar 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 36111);
		} elseif( $userData["status_step"] == 36111 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 36112);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 36112 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 36113);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Варшава,ул.Варшавка,д.25_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 36113 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 36114);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 36114 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 36115);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 36115 ) {
			$domain = $config['domain'][17][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}


		// LLF 2.0

		if ( stripos($Telegram->Text(), "🇦🇿 LALAFO 2.0") || $Telegram->Text() == '🇦🇿 LALAFO 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 61311);
		} elseif( $userData["status_step"] == 61311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 61312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 61312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 61313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Варшава,ул.Варшавка,д.25_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 61313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 61314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 61314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 61315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 61315 ) {
			$domain = $config['domain'][17][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}


        // 999 2.0
        
		if ( stripos($Telegram->Text(), "🇲🇩 999 2.0") || $Telegram->Text() == '🇲🇩 999 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 46311);
		} elseif( $userData["status_step"] == 46311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 46312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 46312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 46313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Варшава,ул.Варшавка,д.25_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 46313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 46314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 46314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 46315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 46315 ) {
			$domain = $config['domain'][14][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// OLX BG 2.0
		if ( stripos($Telegram->Text(), "🇧🇬 OLX BG 2.0") || $Telegram->Text() == '🇧🇬 OLX BG 2.0') {
			$replyData["text"] = " *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 53311);
		} elseif( $userData["status_step"] == 53311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 53312);
				$text = [" *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 53312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 53313);
				$text = [" *Введите* адрес доставки", "", "Пример: _г.Варшава,ул.Варшавка,д.25_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 53313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step",53314);
				$text = [" *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 53314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 53315);
				$text = [" *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 53315 ) {
			$domain = $config['domain'][9][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

        // KUFAR 1.0
		
		if ( stripos($Telegram->Text(), "🇧🇾 Kufar 1.0") || $Telegram->Text() == '🇧🇾 Kufar 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 78501);
		} elseif( $userData["status_step"] == 78501 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 78502);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 78502 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 78503);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 78503 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 78504);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 78504 ) {
			$domain = $config['domain'][5][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

				// EBAY USA 1.0		
		if ( stripos($Telegram->Text(), "EBAY USA 1.0") || $Telegram->Text() == 'EBAY USA 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 7701);
		} elseif( $userData["status_step"] == 7701 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 7702);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 7702 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 7703);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 7703 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 7704);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 7704 ) {
			$domain = $config['domain'][13][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

			// EBAY USA 2.0
		if ( stripos($Telegram->Text(), "EBAY USA 2.0") || $Telegram->Text() == 'EBAY USA 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 8311);
		} elseif( $userData["status_step"] == 8311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8312);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8313);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 8313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8314);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8315);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8315 ) {
			$domain = $config['domain'][13][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

			// EBAY EU 1.0		
		if ( stripos($Telegram->Text(), "EBAY EU 1.0") || $Telegram->Text() == 'EBAY EU 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 2201);
		} elseif( $userData["status_step"] == 2201 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2202);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2202 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2203);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2203 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2204);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2204 ) {
			$domain = $config['domain'][13][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// EBAY EU 2.0
		if ( stripos($Telegram->Text(), "EBAY EU 2.0") || $Telegram->Text() == 'EBAY EU 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 24311);
		} elseif( $userData["status_step"] == 24311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 24312);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 24312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 24313);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 24313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 24314);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 24314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 24315);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 24315 ) {
			$domain = $config['domain'][13][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// DHL USA
		if ( stripos($Telegram->Text(), "DHL USA") || $Telegram->Text() == 'DHL USA') {		
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 42711);	
		} elseif( $userData["status_step"] == 42711) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42712);
				$text = ["♻️ *Введите* вес товара", "*Пример:* 0.5 кг"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42712 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "weight", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42713);
				$text = ["♻️ *Введите* дату отправления", "*Сегодня:* ".date("d.m.y").""];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42713 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42714);
				$text = ["♻️ *Введите* дату получения", "*Сегодня:* ".date("d.m.y").""];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42714 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_receive", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42715);
				$text = ["♻️ *Введите* ФИО от кого отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42715 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_initial", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42716);
				$text = ["♻️ *Введите* откуда отправлен товар", "г.Москва, ул Ленина 12."];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42716 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_city", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42717);
				$text = ["♻️ *Введите* ФИО кому отправлен товар", "Ф.И.О мамонта"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42717 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "to_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42718);
				$text = ["♻️ *Введите* адрес мамонта куда будет отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42718 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42719);
				$text = ["♻️ *Введите* номер телефона мамонта", "Пример: +1 (000) 000 00 00"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42719 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 42720);
				$text = ["♻️ *Введите* сумму товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 42720 ) {
			changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 0);
			$domain = $config['domain'][14][0];
			
            $name = getTemp($Telegram->ChatID(), "name"); // название
			$sum = $Telegram->Text(); // сумма
			$weight = getTemp($Telegram->ChatID(), "weight"); // вес
			$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
			$receive = getTemp($Telegram->ChatID(), "date_receive"); // Время прибытия
			$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
			$from_initial = getTemp($Telegram->ChatID(), "from_initial"); // Время отправления
			$from_city = getTemp($Telegram->ChatID(), "from_city"); // Откуда отправлен товар
			$to_send = getTemp($Telegram->ChatID(), "to_send"); // Кому отправлен товар
			$address = getTemp($Telegram->ChatID(), "address"); // Адрес доставки
			$number = getTemp($Telegram->ChatID(), "number"); // Адрес доставки
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
                "id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"initials" => $initials,
				"address" => $address,
				"weight" => $weight,
				"sended" => $sended,
				"receive" => $receive,
				"from_initial" => $from_initial,
				"from_city" => $from_city,
				"to_send" => $to_send,
				"number" => $number,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/track'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";
			$dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// DHL EU
		if ( stripos($Telegram->Text(), "DHL EU") || $Telegram->Text() == 'DHL EU') {		
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 55711);	
		} elseif( $userData["status_step"] == 55711) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55712);
				$text = ["♻️ *Введите* вес товара", "*Пример:* 0.5 кг"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55712 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "weight", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55713);
				$text = ["♻️ *Введите* дату отправления", "*Сегодня:* ".date("d.m.y").""];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55713 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55714);
				$text = ["♻️ *Введите* дату получения", "*Сегодня:* ".date("d.m.y").""];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55714 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_receive", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55715);
				$text = ["♻️ *Введите* ФИО от кого отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55715 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_initial", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55716);
				$text = ["♻️ *Введите* откуда отправлен товар", "г.Москва, ул Ленина 12."];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55716 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_city", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55717);
				$text = ["♻️ *Введите* ФИО кому отправлен товар", "Ф.И.О мамонта"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55717 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "to_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55718);
				$text = ["♻️ *Введите* адрес мамонта куда будет отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55718 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55719);
				$text = ["♻️ *Введите* номер телефона мамонта", "Пример: +1 (000) 000 00 00"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55719 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55720);
				$text = ["♻️ *Введите* сумму товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55720 ) {
			changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 0);
			$domain = $config['domain'][15][0];
			
            $name = getTemp($Telegram->ChatID(), "name"); // название
			$sum = $Telegram->Text(); // сумма
			$weight = getTemp($Telegram->ChatID(), "weight"); // вес
			$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
			$receive = getTemp($Telegram->ChatID(), "date_receive"); // Время прибытия
			$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
			$from_initial = getTemp($Telegram->ChatID(), "from_initial"); // Время отправления
			$from_city = getTemp($Telegram->ChatID(), "from_city"); // Откуда отправлен товар
			$to_send = getTemp($Telegram->ChatID(), "to_send"); // Кому отправлен товар
			$address = getTemp($Telegram->ChatID(), "address"); // Адрес доставки
			$number = getTemp($Telegram->ChatID(), "number"); // Адрес доставки
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
                "id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"initials" => $initials,
				"address" => $address,
				"weight" => $weight,
				"sended" => $sended,
				"receive" => $receive,
				"from_initial" => $from_initial,
				"from_city" => $from_city,
				"to_send" => $to_send,
				"number" => $number,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/track'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";
			$dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// LEBONCOIN 1.0		
		if ( stripos($Telegram->Text(), "LEBONCOIN 1.0") || $Telegram->Text() == 'LEBONCOIN 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 2401);
		} elseif( $userData["status_step"] == 2401 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2402);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2402 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2403);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2403 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2404);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2404 ) {
			$domain = $config['domain'][16][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// QUOKA 1.0		
		if ( stripos($Telegram->Text(), "Ebay 1.0") || $Telegram->Text() == 'Ebay 1.0') {
			$replyData["text"] = " *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 2101);
		} elseif( $userData["status_step"] == 2101 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2102);
				$text = ["️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2102 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2103);
				$text = [" *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2103 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2104);
				$text = [" *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2104 ) {
			$domain = $config['domain'][17][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// SUBITO 1.0		
		if ( stripos($Telegram->Text(), "SUBITO 1.0") || $Telegram->Text() == 'SUBITO 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 3701);
		} elseif( $userData["status_step"] == 3701 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3702);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3702 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3703);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3703 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3704);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3704 ) {
			$domain = $config['domain'][18][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// WILLHABEN 1.0		
		if ( stripos($Telegram->Text(), "WILLHABEN 1.0") || $Telegram->Text() == 'WILLHABEN 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 5501);
		} elseif( $userData["status_step"] == 5501 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5502);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5502 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5503);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5503 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5504);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5504 ) {
			$domain = $config['domain'][19][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// LEBONCOIN 2.0
		if ( stripos($Telegram->Text(), "LEBONCOIN 2.0") || $Telegram->Text() == 'LEBONCOIN 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 2211);
		} elseif( $userData["status_step"] == 2211 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2212);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2212 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2213);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 2213 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2214);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2214 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2215);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2215 ) {
			$domain = $config['domain'][16][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// QUOKA 2.0
		if ( stripos($Telegram->Text(), "Ebay 2.0") || $Telegram->Text() == 'Ebay 2.0') {
			$replyData["text"] = " *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 5411);
		} elseif( $userData["status_step"] == 5411 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5412);
				$text = [" *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5412 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5413);
				$text = ["️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 5413 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5414);
				$text = ["️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5414 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5415);
				$text = ["️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5415 ) {
			$domain = $config['domain'][17][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// SUBITO 2.0
		if ( stripos($Telegram->Text(), "SUBITO 2.0") || $Telegram->Text() == 'SUBITO 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 6911);
		} elseif( $userData["status_step"] == 6911 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6912);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6912 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6913);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 6913 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6914);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6914 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6915);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6915 ) {
			$domain = $config['domain'][18][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// WILLHABEN 2.0
		if ( stripos($Telegram->Text(), "🇷🇴 Фан Courier") || $Telegram->Text() == '🇷🇴 Фан Courier') {
			$replyData["text"] = " *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 2911);
		} elseif( $userData["status_step"] == 2911 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2912);
				$text = [" *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2912 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2913);
				$text = [" *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 2913 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2914);
				$text = ["️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2914 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2915);
				$text = [" *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2915 ) {
			$domain = $config['domain'][19][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} LEI";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// RICARDO 1.0		
		if ( stripos($Telegram->Text(), "RICARDO 1.0") || $Telegram->Text() == 'RICARDO 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 9901);
		} elseif( $userData["status_step"] == 9901 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 9902);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 9902 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 9903);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 9903 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 9904);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 9904 ) {
			$domain = $config['domain'][23][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CHF";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// FAN COURIER 1.0		
		if ( stripos($Telegram->Text(), "FAN COURIER 1.0") || $Telegram->Text() == 'FAN COURIER 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 6601);
		} elseif( $userData["status_step"] == 6601 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6602);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6602 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6603);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6603 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6604);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6604 ) {
			$domain = $config['domain'][20][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} RON";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// RICARDO 2.0
		if ( stripos($Telegram->Text(), "📦 DHL DE") || $Telegram->Text() == '📦 DHL DE') {
			$replyData["text"] = " *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 66311);
		} elseif( $userData["status_step"] == 66311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 66312);
				$text = [" *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 66312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 66313);
				$text = [" *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 66313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 66314);
				$text = [" *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 66314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 66315);
				$text = ["️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 66315 ) {
			$domain = $config['domain'][23][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'></a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// FAN COURIER 2.0
		if ( stripos($Telegram->Text(), "FAN COURIER 2.0") || $Telegram->Text() == 'FAN COURIER 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 55311);
		} elseif( $userData["status_step"] == 55311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55312);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55313);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 55313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55314);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55315);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55315 ) {
			$domain = $config['domain'][20][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} RON";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// KIJIJI 1.0		
		if ( stripos($Telegram->Text(), "KIJIJI 1.0") || $Telegram->Text() == 'KIJIJI 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 6101);
		} elseif( $userData["status_step"] == 6101 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6102);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6102 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6103);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6103 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6104);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6104 ) {
			$domain = $config['domain'][21][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CAD";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}


		// JOFOGAS 1.0		
		if ( stripos($Telegram->Text(), "JOFOGAS 1.0") || $Telegram->Text() == 'JOFOGAS 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 96101);
		} elseif( $userData["status_step"] == 96101 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96102);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 96102 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96103);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 96103 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96104);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 96104 ) {
			$domain = $config['domain'][25][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CAD";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// MILANUNCIOS 1.0		
		if ( stripos($Telegram->Text(), "MILANUNCIOS 1.0") || $Telegram->Text() == 'MILANUNCIOS 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 8101);
		} elseif( $userData["status_step"] == 8101 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8102);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8102 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8103);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8103 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8104);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8104 ) {
			$domain = $config['domain'][22][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// CORREOS 1.0		
		if ( stripos($Telegram->Text(), "CORREOS 1.0") || $Telegram->Text() == 'CORREOS 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 98101);
		} elseif( $userData["status_step"] == 98101 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 98102);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 98102 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 98103);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 98103 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 98104);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 98104 ) {
			$domain = $config['domain'][26][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// TORI 1.0		
		if ( stripos($Telegram->Text(), "TORI 1.0") || $Telegram->Text() == 'TORI 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 6201);
		} elseif( $userData["status_step"] == 6201 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6202);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6202 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6203);
				$text = ["♻️ *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6203 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6204);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6204 ) {
			$domain = $config['domain'][24][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CAD";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}					
		
		// OLX PL 1.0
		
		if ( stripos($Telegram->Text(), "🇵🇱 OLX PL 1.0") || $Telegram->Text() == '🇵🇱 OLX PL 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 14501);
		} elseif( $userData["status_step"] == 14501 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 14502);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 14502 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 14503);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 14503 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 14504);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 14504 ) {
			$domain = $config['domain'][8][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// SB 1.0
		
		if ( stripos($Telegram->Text(), "🇨🇿 SBazar 1.0") || $Telegram->Text() == '🇨🇿 SBazar 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 54101);
		} elseif( $userData["status_step"] == 54101 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 54102);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 54102 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 54103);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 54103 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 54104);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 54104 ) {
			$domain = $config['domain'][17][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// LLF 1.0
		
		if ( stripos($Telegram->Text(), "🇦🇿 LALAFO 1.0") || $Telegram->Text() == '🇦🇿 LALAFO 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 41501);
		} elseif( $userData["status_step"] == 41501 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 41502);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 41502 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 41503);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 41503 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 41504);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 41504 ) {
			$domain = $config['domain'][17][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}


		// 999 1.0
		
		if ( stripos($Telegram->Text(), "🇲🇩 999 1.0") || $Telegram->Text() == '🇲🇩 999 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 55001);
		} elseif( $userData["status_step"] == 55001 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55002);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55002 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55003);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55003 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 55004);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 55004 ) {
			$domain = $config['domain'][14][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// OLX RO 1.0
		
		if ( stripos($Telegram->Text(), "🇷🇴 OLX RO 1.0") || $Telegram->Text() == '🇷🇴 OLX RO 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 4762);
		} elseif( $userData["status_step"] == 4762) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4763);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4763) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4764);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4764) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4765);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4765) {
			$domain = $config['domain'][7][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// OLX BG 1.0
		
		if ( stripos($Telegram->Text(), "🇧🇬 OLX BG 1.0") || $Telegram->Text() == '🇧🇬 OLX BG 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 64762);
		} elseif( $userData["status_step"] == 64762) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 64763);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 64763) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 64764);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 64764) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 64765);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 64765) {
			$domain = $config['domain'][9][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// KIJIJI 2.0
		if ( stripos($Telegram->Text(), "KIJIJI 2.0") || $Telegram->Text() == 'KIJIJI 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 2411);
		} elseif( $userData["status_step"] == 2411 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2412);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2412 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2413);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 2413 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2414);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2414 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 2415);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 2415 ) {
			$domain = $config['domain'][21][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CAD";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// JOFOGAS 2.0
		if ( stripos($Telegram->Text(), "JOFOGAS 2.0") || $Telegram->Text() == 'JOFOGAS 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 82411);
		} elseif( $userData["status_step"] == 82411 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 82412);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 82412 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 82413);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 82413 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 82414);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 82414 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 82415);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 82415 ) {
			$domain = $config['domain'][25][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CAD";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// MILANUNCIOS 2.0
		if ( stripos($Telegram->Text(), "MILANUNCIOS 2.0") || $Telegram->Text() == 'MILANUNCIOS 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 6611);
		} elseif( $userData["status_step"] == 6611 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6612);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6612 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6613);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 6613 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6614);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6614 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 6615);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 6615 ) {
			$domain = $config['domain'][22][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
        // CORREOS 2.0
		if ( stripos($Telegram->Text(), "CORREOS 2.0") || $Telegram->Text() == 'CORREOS 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 96611);
		} elseif( $userData["status_step"] == 96611 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96612);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 96612 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96613);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 96613 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96614);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 96614 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 96615);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 96615 ) {
			$domain = $config['domain'][26][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EUR";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// TORI 2.0
		if ( stripos($Telegram->Text(), "TORI 2.0") || $Telegram->Text() == 'TORI 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["🔙 Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 8911);
		} elseif( $userData["status_step"] == 8911 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8912);
				$text = ["♻️ *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8912 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8913);
				$text = ["♻️ *Введите* адрес доставки", "", "Пример: _г.Кызылорда, Кызылординская область, улица.Ынтымак 10, кв 2_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 8913 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8914);
				$text = ["♻️ *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8914 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8915);
				$text = ["♻️ *Прикрепите* изображение товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["🔙 Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8915 ) {
			$domain = $config['domain'][24][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} CAD";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// OLX KZ 1.0
		
		if ( stripos($Telegram->Text(), "🇰🇿 OLX KZ 1.0") || $Telegram->Text() == '🇰🇿 OLX KZ 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 3501);
		} elseif( $userData["status_step"] == 3501 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3502);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3502 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3503);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3503 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3504);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3504 ) {
			$domain = $config['domain'][6][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
        // OLX PT 1.0
		
		if ( stripos($Telegram->Text(), "🇵🇹 OLX PT 1.0") || $Telegram->Text() == '🇵🇹 OLX PT 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 58501);
		} elseif( $userData["status_step"] == 58501 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 58502);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 58502 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 58503);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 58503 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 58504);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 58504 ) {
			$domain = $config['domain'][11][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}


        // OLX UZ 1.0
		
		if ( stripos($Telegram->Text(), "🇺🇿 OLX UZ 1.0") || $Telegram->Text() == '🇺🇿 OLX UZ 1.0') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step",8201);
		} elseif( $userData["status_step"] == 8201 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8202);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8202 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8203);
				$text = ["🪐 *Укажите количество дней доставки*", "","`Пример : от 1 до 3 дней`'"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8203 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8204);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8204 ) {
			$domain = $config['domain'][10][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// DPD
		if ( stripos($Telegram->Text(), "DPD") || $Telegram->Text() == 'DPD') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 95311);
		} elseif( $userData["status_step"] == 95311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 95312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 95312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 95313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Кунда,ул.Желтая,д.24_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 95313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 95314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 95314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 95315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 95315 ) {
			$domain = $config['domain'][27][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EURO";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a><b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// inpost
		if ( stripos($Telegram->Text(), "InPost") || $Telegram->Text() == 'InPost') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 25311);
		} elseif( $userData["status_step"] == 25311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 25312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 25312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 25313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Варшава,ул.Вроцлака,д.45_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 25313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 25314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 25314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 25315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 25315 ) {
			$domain = $config['domain'][28][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum} EURO";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a><b><a href='https://{$domain}/{$cumID}/receive'>Получение</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}

		// NOVAPOSHTA
		if ( stripos($Telegram->Text(), "🇺🇦 NOVAPOSHTA") || $Telegram->Text() == '🇺🇦 NOVAPOSHTA') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 15311);
		} elseif( $userData["status_step"] == 15311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 15312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 15312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 15313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Киев,ул.Весенняя,д.24_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 15313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 15314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 15314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 15315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 15315 ) {
			$domain = $config['domain'][4][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// ЕвроПочта
		if ( stripos($Telegram->Text(), "ЕвроПочта") || $Telegram->Text() == 'ЕвроПочта') {
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 85311);
		} elseif( $userData["status_step"] == 85311 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 85312);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 85312 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 85313);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _г.Киев,ул.Весенняя,д.24_"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 85313 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 85314);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb")]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				closeThread();
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 85314 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 85315);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 85315 ) {
			$domain = $config['domain'][13][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		
		
		
		
		
		/*
		 * Youla 2.0
		 */
		if ( stripos($Telegram->Text(), "🇷🇺 Youla 2.0") || $Telegram->Text() == '🇷🇺 Youla 2.0') {
			$replyData["text"] = "🪐 *Введите* название товар";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 341);
		} elseif( $userData["status_step"] == 341 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 342);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 342 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 343);
				$text = ["🪐 *Введите* адрес доставки", "", "Пример: _640012, г.Новосибирск, ул Трудовая 20, кв 5_"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 343 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 344);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 344 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 345);
				$text = ["🪐 *Пришлите изображение товара* ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 345 ) {
			$domain = $config['domain'][1][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$textOnCreated = ["➕ *Ваше объявление успешно создано*", "",
							  "Название: *{$name}*",
							  "Сумма: *{$sum} RUB*",
							  "ID: *{$cumID}*","",
							  "[Получение](https://{$domain}/{$cumID}/receive)  |  [Возврат](https://{$domain}/{$cumID}/return)",
							  "[Курьерская служба](https://{$domain}/{$cumID}/delivery)",
							  "",
							  "⚠️ *Внимание!* Это объявление будет действительно 48 часов. Далее будет перенесено в архив."];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textOnCreated), "parse_mode" => "Markdown",
									"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true]), "disable_web_page_preview" =>true]);
			$qc = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode("https://{$domain}/{$cumID}/receive");
			$ssdata = ["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
			closeThread();
		}

		if ( stripos($Telegram->Text(), "🇷🇺 Youla 1.0") || $Telegram->Text() == '🇷🇺 Youla 1.0') {
			$replyData["text"] = "👇 *Введите* название объявления";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 351);
		} elseif( $userData["status_step"] == 351 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 352);
				$text = ["👇 *Введите* сумму товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 352 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 353);
				$text = ["👇 *Введите* кол-во дней доставки", "Пример: *1 день - от 2 до 3 дней*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 353 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 354);
				$text = ["👇 *Вставьте ссылку на фотографию* ", "Можете использовать [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 354 ) {
			$domain = $config['domain'][1][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$textOnCreated = ["➕ *Ваше объявление успешно создано*", "",
							  "Название: *{$name}*",
							  "Сумма: *{$sum} RUB*",
							  "ID: *{$cumID}*","",
							  "[Оплата](https://{$domain}/{$cumID}/order)  —  [Возврат](https://{$domain}/{$cumID}/return)",
							  "",
							  "⚠️ *Внимание!* Это объявление будет действительно 48 часов. Далее будет перенесено в архив."];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textOnCreated), "parse_mode" => "Markdown",
									"disable_web_page_preview" => true,
									"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true])]);
			$qc = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode("https://{$domain}/{$cumID}/order");
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => "[QR-Code для оплаты]($qc)", "parse_mode" => "Markdown"]);
		}
		
		// Циан 2.0
		if ( stripos($Telegram->Text(), "Циан 2.0") || $Telegram->Text() == 'Циан 2.0') {
			$replyData["text"] = "🪐 *Введите* название недвижимости";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 4971);
		} elseif( $userData["status_step"] == 4971 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4972);
				$text = ["🪐 *Введите* стоимость полной сделки"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4972 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4973);
				$text = ["🪐 *Введите* адрес квартиры","","Пример: _614000, г.Новосибирск, ул Ленина 2_"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 4973 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4974);
				$text = ["🪐 *Введите* ФИО кто снимает помещение","", "Пример: *Иванов Иван Иванович*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4974 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 4975);
				$text = ["🪐 *Пришлите* изображение помещения ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown","reply_markup"=>json_encode(["resize_keyboard"=>true,'keyboard'=>[["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 4975 ) {
			$domain = $config['domain'][15][0];
			$domain_booking = $config['domain'][8][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$src = "https://{$domain}";
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		if ( stripos($Telegram->Text(), "RIA Недвижимость") || $Telegram->Text() == 'RIA Недвижимость') {
			$replyData["text"] = "🪐 *Введите* название недвижимости";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 3971);
		} elseif( $userData["status_step"] == 3971 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3972);
				$text = ["🪐 *Введите* стоимость полной сделки"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3972 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3973);
				$text = ["🪐 *Введите* адрес квартиры","","Пример: _614000, г.Новосибирск, ул Ленина 2_"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 3973 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3974);
				$text = ["🪐 *Введите* ФИО кто снимает помещение","", "Пример: *Иванов Иван Иванович*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3974 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3975);
				$text = ["🪐 *Пришлите* изображение помещения ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown","reply_markup"=>json_encode(["resize_keyboard"=>true,'keyboard'=>[["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3975 ) {
			$domain = $config['domain'][16][0];
			$domain_booking = $config['domain'][8][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$src = "https://{$domain}";
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";

			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		// RIA Недвижимость
		
		
		// YOULA НЕДВИЖИМОСТЬ
		if ( $Telegram->Text() == 'Юла Недвижимость 2.0') {
			$replyData["text"] = "🪐 *Введите* название недвижимости";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 22371);
		} elseif( $userData["status_step"] == 22371 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 22372);
				$text = ["🪐 *Введите* стоимость полной сделки"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 22372 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 22373);
				$text = ["🪐 *Введите* адрес квартиры","","Пример: _614000, г.Новосибирск, ул Ленина 2_"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 22373 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 22374);
				$text = ["🪐 *Введите* ФИО кто снимает помещение","", "Пример: *Иванов Иван Иванович*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 22374 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 22375);
				$text = ["🪐 *Пришлите* изображение помещения ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown","reply_markup"=>json_encode(["resize_keyboard"=>true,'keyboard'=>[["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 22375 ) {
			$domain = $config['domain'][1][0];
			$domain_booking = $config['domain'][8][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$src = "https://{$domain}";
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		
		if ( $Telegram->Text() == 'Недвижимость 2.0') {
			$replyData["text"] = "🪐 *Введите* название недвижимости";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 371);
		} elseif( $userData["status_step"] == 371 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 372);
				$text = ["🪐 *Введите* стоимость полной сделки"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 372 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 373);
				$text = ["🪐 *Введите* адрес квартиры","","Пример: _614000, г.Новосибирск, ул Ленина 2_"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 373 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 374);
				$text = ["🪐 *Введите* ФИО кто снимает помещение","", "Пример: *Иванов Иван Иванович*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 374 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 375);
				$text = ["🪐 *Пришлите* изображение помещения ", "", "`Отправьте боту изображение (допускается любой бот)`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown","reply_markup"=>json_encode(["resize_keyboard"=>true,'keyboard'=>[["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 375 ) {
			$domain = $config['domain'][0][0];
			$domain_booking = $config['domain'][8][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$src = "https://{$domain}";
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		if ( $Telegram->Text() == 'Трек номер') {
			$replyData["text"] = "🪐 *Введите* наименование товара";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["reply_markup"] = json_encode(["resize_keyboard"=>true, "keyboard" => [["Назад"]]]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 401);	
		} elseif( $userData["status_step"] == 401) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 402);
				$text = ["🪐 *Введите* вес товара","","Указывайте примерный вес товара в валюте КГ, пример 0.5 кг"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 402 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "weight", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 403);
				$text = ["🪐 *Укажите* дату отправления товара","", "Сегодня ".date("d.m.y")];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 403 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 404);
				$text = ["🪐 *Укажите* дату получения товара","", "Завтра ".date("d.m.y",strtotime("+1 day"))];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 404 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_receive", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 405);
				$text = ["🪐 *Введите* ФИО отправителя","","Указывайте инициалы в формате Иванов Иван Иванович"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 405 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_initial", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 406);
				$text = ["🪐 *Введите* откуда отправлен товар","", "644012, г.Новосибирск, ул Ленина 12"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 406 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_city", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 407);
				$text = ["🪐 *Введите* ФИО кому отправлен товар", "", "Указывайте инициалы в формате Иванов Иван Иванович"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 407 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "to_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 408);
				$text = ["🪐 *Введите* адрес куда отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 408 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 409);
				$text = ["🪐 *Укажите* номер телефона получателя товара", "", "Указывайте номер телефона в международном формате\nПример +7 (000) 000 00 00"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 409 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 410);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 410 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 0);
				
				$name = getTemp($Telegram->ChatID(), "name"); // название
				$sum = $Telegram->Text(); // сумма
				$weight = getTemp($Telegram->ChatID(), "weight"); // вес
				$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
				$receive = getTemp($Telegram->ChatID(), "date_receive"); // Время прибытия
				$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
				$from_initial = getTemp($Telegram->ChatID(), "from_initial"); // Время отправления
				$from_city = getTemp($Telegram->ChatID(), "from_city"); // Откуда отправлен товар
				$to_send = getTemp($Telegram->ChatID(), "to_send"); // Кому отправлен товар
				$address = getTemp($Telegram->ChatID(), "address"); // Адрес доставки
				$number = getTemp($Telegram->ChatID(), "number"); // Адрес доставки
				
				$track = getTrackNumber();
				
				$src = "https://{$domain}";
								
				$trackData = [
					"name" => $name,
					"sum" => $sum,
					"src" => $src,
					"weight" => $weight,
					"sended" => $sended,
					"receive" => $receive,
					"sended" => $sended,
					"time_created" => time(),
					"from_initial" => $from_initial,
					"from_city" => $from_city,
					"to_send" => $to_send,
					"address" => $address,
					"number" => $number,
					"track" => $track,
					"chat_id" => $Telegram->ChatID()
				];
				
				addCum($track, $trackData);
				
				$domain = $config['domain'][2][0];
				$domain1 = $config['domain'][3][0];
				$domain2 = $config['domain'][9][0];
				
				$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
				$dText[] = "🎉 <b>Трек номер</b> успешно создан\n";
				$dText[] = "Трек номер: {$track}";
				$dText[] = "Название товара: {$name}";
				$dText[] = "Стоимость товара: {$sum} RUB\n";
				$dText[] = "🚒 Boxberry  <b><a href='https://{$domain1}/{$track}/track'>Оплата</a></b> / <b><a href='https://{$domain1}/{$track}/receive'>Получение</a></b>";
				$dText[] = "🚛 CDEK  <b><a href='https://{$domain}/{$track}/track'>Оплата</a></b> / <b><a href='https://{$domain}/{$track}/receive'>Получение</a></b>";
				$dText[] = "🚕 Яндекс  <b><a href='https://{$domain3}/{$track}/track'>Оплата</a></b> / <b><a href='https://{$domain3}/{$track}/receive'>Получение</a></b>";
				$dText[] = "\n<i>Этот трек номер будет действителен 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
				
				$obj2["text"] = getMessage($dText);
				$obj2["parse_mode"] = "html";
				$obj2["chat_id"] = $Telegram->ChatID();
				$obj2["disable_web_page_preview"] = true;
				$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$track}", "text" => "🐬 Запрашивать баланс"]]]]);
				
				$Telegram->sendMessage($obj2);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		if ( stripos($Telegram->Text(), "Boxberry 1.0/2.0") || $Telegram->Text() == 'Boxberry 1.0/2.0') {
			$replyData["text"] = "👇 *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 421);	
		} elseif( $userData["status_step"] == 421) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 422);
				$text = ["👇 *Введите* вес товара", "Пример: `0.5 кг`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 422 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "weight", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 423);
				$text = ["👇 *Введите* дату отправления", "Сегодня: `".date("d.m.y")."`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 423 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 404);
				$text = ["👇 *Введите* дату получения", "Сегодня: `".date("d.m.y")."`"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 424 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "date_receive", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 425);
				$text = ["👇 *Введите* ФИО от кого отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 425 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_initial", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 426);
				$text = ["👇 *Введите* откуда отправлен товар", "г.Москва, ул Ленина 12."];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 426 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "from_city", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 427);
				$text = ["👇 *Введите* ФИО кому отправлен товар", "Ф.И.О мамонта"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 427 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "to_send", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 428);
				$text = ["👇 *Введите* адрес мамонта куда будет отправлен товар"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 428 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 429);
				$text = ["👇 *Введите* номер телефона мамонта", "Пример: +7 (000) 000 00 00"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 429 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 430);
				$text = ["👇 *Введите* сумму товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 430 ) {
			if ( strlen($Telegram->Text()) > 2 ) {
				changeTemp($Telegram->ChatID(), "number", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 0);
				
				$name = getTemp($Telegram->ChatID(), "name"); // название
				$sum = $Telegram->Text(); // сумма
				$weight = getTemp($Telegram->ChatID(), "weight"); // вес
				$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
				$receive = getTemp($Telegram->ChatID(), "date_receive"); // Время прибытия
				$sended = getTemp($Telegram->ChatID(), "date_send"); // Время отправления
				$from_initial = getTemp($Telegram->ChatID(), "from_initial"); // Время отправления
				$from_city = getTemp($Telegram->ChatID(), "from_city"); // Откуда отправлен товар
				$to_send = getTemp($Telegram->ChatID(), "to_send"); // Кому отправлен товар
				$address = getTemp($Telegram->ChatID(), "address"); // Адрес доставки
				$number = getTemp($Telegram->ChatID(), "number"); // Адрес доставки
				
				$track = "0000".mt_rand(111111111, 999999999);
				
				$src = "https://{$domain}";
				
				$trackData = [
					"name" => $name,
					"src" => $src,
					"sum" => $sum,
					"weight" => $weight,
					"sended" => $sended,
					"receive" => $receive,
					"from_initial" => $from_initial,
					"from_city" => $from_city,
					"to_send" => $to_send,
					"address" => $address,
					"number" => $number,
					"track" => $track,
					"chat_id" => $Telegram->ChatID()
				];
				
				addCum($track, $trackData);
				
				$domain = $config['domain'][2][0];
				
				$textOnCreated = ["➕ *Ваш трек успешно создан*", "",
							  "Название: *{$name}*",
							  "Сумма: *{$sum} RUB*",
							  "Track ID: *{$track}*","",
							  "[Оплата](https://{$domain}/{$track}/track)  | [Получение](https://{$domain}/{$track}/receive)  |  [Возврат](https://{$domain}/{$cumID}/return)",
							  "",
							  "⚠️ *Внимание!* Это объявление будет действительно 48 часов. Далее будет перенесено в архив."];
				
				
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textOnCreated), "parse_mode" => "Markdown",
									"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
			
		}
		}
		
		if ( $Telegram->Text() == "Прочее" ) {
			$ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["👇 *Выберите* сервис"]),"parse_mode" => "Markdown"];
			$ResultMessage['reply_markup'] = json_encode(["keyboard" => [["BlaBlaCar 1.0", "Циан 1.0", "Drom 2.0"],["OLX 2.0", "OLX 1.0", "✈️ Назад"],["OLX Недвижимость","🇧🇾 Kufar 2.0"]], "resize_keyboard" => true, "one_time_keyboard" => true]); 
			$Telegram->sendMessage($ResultMessage);
		}
		
		if ( $Telegram->Text() == "Циан 1.0" ) {
			$ResultMessage = ["chat_id" => $Telegram->ChatID(),"text" => getMessage(["☘️ *Сервис недоступен*", "Сервис в разработке, скоро будет!"]),"parse_mode" => "Markdown"];
			$Telegram->sendMessage($ResultMessage);
		}
		
		if ( stripos($Telegram->Text(), "Дром 2.0") || $Telegram->Text() == 'Дром 2.0') {			
			$replyData["text"] = "♻️ *Введите название товара*";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 581);
		} elseif( $userData["status_step"] == 581 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 582);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 582 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 583);
				$text = ["🪐 *Введите* адрес доставки","", "Пример: *644013, Новосибирск, ул Ленина 12*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 583 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 584);
				$text = ["🪐 *Введите* ФИО получателя","", "Указывайте в формате *Иванов Иван Иванович*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 584 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 585);
				$text = ["🪐 *Вставьте* ссылку на фотографию ","", "Используйте бота [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']}), укажите здесь ссылку полученную от бота."];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 585 ) {
			$domain = $config['domain'][7][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
		
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);

			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		if ( stripos($Telegram->Text(), "OLX Недвижимость") || $Telegram->Text() == 'OLX Недвижимость') {
			$replyData["text"] = "👇 *Введите* название недвижимости";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 8371);
		} elseif( $userData["status_step"] == 8371 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8372);
				$text = ["👇 *Введите* сумму"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8372 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8373);
				$text = ["👇 *Введите* адрес жилья", "Пример: *ул Красноармейская 13, кв 2*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 8373 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8374);
				$text = ["👇 *Введите* ваше ФИО", "Пример: *Савельев Алексей Николаевич*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8374 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 8375);
				$text = ["👇 *Вставьте ссылку на фотографию* ", "Можете использовать [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 8375 ) {
			$domain = $config['domain'][4][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$src = "https://{$domain}";
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$textOnCreated = ["➕ *Ваше объявление успешно создано*", "",
							  "Название: *{$name}*",
							  "Сумма: *{$sum} UAH*",
							  "ID: *{$cumID}*","",
							  "[Получение](http://{$domain}/{$cumID}/rent)  |  [Возврат](http://{$domain}/{$cumID}/return)\n",
							  "",
							  "⚠️ *Внимание!* Это объявление будет действительно 48 часов. Далее будет перенесено в архив."];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textOnCreated), "parse_mode" => "Markdown",
									"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true]), "disable_web_page_preview" =>true]);
			$qc = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode("http://{$domain}/{$cumID}/rent");
			$ssdata = ["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
			closeThread();
		}


        if ( stripos($Telegram->Text(), "OLX KZ Недвижимость") || $Telegram->Text() == 'OLX KZ Недвижимость') {
			$replyData["text"] = "👇 *Введите* название недвижимости";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 88371);
		} elseif( $userData["status_step"] == 88371 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 88372);
				$text = ["👇 *Введите* сумму"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 88372 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 88373);
				$text = ["👇 *Введите* адрес жилья", "Пример: *ул Красноармейская 13, кв 2*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 88373 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 88374);
				$text = ["👇 *Введите* ваше ФИО", "Пример: *Савельев Алексей Николаевич*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 88374 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 88375);
				$text = ["👇 *Вставьте ссылку на фотографию* ", "Можете использовать [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 88375 ) {
			$domain = $config['domain'][14][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$src = "https://{$domain}";
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$textOnCreated = ["➕ *Ваше объявление успешно создано*", "",
							  "Название: *{$name}*",
							  "Сумма: *{$sum} KZT*",
							  "ID: *{$cumID}*","",
							  "[Получение](http://{$domain}/{$cumID}/rent)  |  [Возврат](http://{$domain}/{$cumID}/return)\n",
							  "",
							  "⚠️ *Внимание!* Это объявление будет действительно 48 часов. Далее будет перенесено в архив."];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textOnCreated), "parse_mode" => "Markdown",
									"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true]), "disable_web_page_preview" =>true]);
			$qc = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode("http://{$domain}/{$cumID}/rent");
			$ssdata = ["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
			closeThread();
		}



		
		if ( stripos($Telegram->Text(), "🇺🇦 OLX 2.0") || $Telegram->Text() == '🇺🇦 OLX 2.0') {
			$replyData["text"] = "♻️ *Введите название товара*\n\nМожете вставить ссылку на объявления в формате: https://olx.ua/item/data";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData['reply_markup'] = json_encode(["keyboard" => [["Назад"]], "resize_keyboard" => true]);
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 3331);
		} elseif( $userData["status_step"] == 3331 ) {
			if ( array_key_exists('entities', $Telegram->getData()['message']) ) {
				$o = $Telegram->getData()['message']['entities'][0];
				if ( $o['type'] == 'url' ) {
					$url_olx = substr($Telegram->Text(), $o['offset'], $o['length']);
					$dataOLX = parseOLX($url_olx);
					if ( $dataOLX['name'] == '' ) {
						$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "🤷 Не удается получить название товара"]);
						closeThread();
					}
					changeTemp($Telegram->ChatID(), "name", $dataOLX['name']);
					changeTemp($Telegram->ChatID(), "sum", $dataOLX['sum']);
					changeTemp($Telegram->ChatID(), "image", $dataOLX['image']);
					file_put_contents("Pattern/KASTIL/{$Telegram->UserID()}_olx", $dataOLX['image']);
					$text = ["🪐 *Введите* адрес доставки", "", "Пример: _640012, г.Новосибирск, ул Трудовая 20, кв 5_"];
					$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
					changeValue($Telegram->ChatID(), "status_step", 3333);
				} else {
					$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "🤷 Не удается открыть ссылку"]);
						closeThread();
				}
			} else {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3332);
				$text = ["🪐 *Введите стоимость товара*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			}
		} elseif( $userData["status_step"] == 3332 ) {
			changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
			changeValue($Telegram->ChatID(), "status_step", 3333);
			$text = ["🪐 *Введите* адрес доставки", "", "Пример: _640012, г.Новосибирск, ул Трудовая 20, кв 5_"];
			$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
			
			if ( getTemp($Telegram->ChatID(), "address_kb") != '' )
				$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "address_kb")]], "resize_keyboard"=>true]);
			
			$Telegram->sendMessage($ss);
		}elseif( $userData["status_step"] == 3333 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3334);
				$text = ["🪐 *Введите* ФИО получателя", "", "Пример: *Иванов Иван Иванович*"];
				$ss = ["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"];
				
				if ( getTemp($Telegram->ChatID(), "initials_kb") != '' )
					$ss['reply_markup'] = json_encode(["keyboard" => [[getTemp($Telegram->ChatID(), "initials_kb"), "Назад"]], "resize_keyboard"=>true,"one_time_keyboard"=>true]);
				
				$Telegram->sendMessage($ss);
				
				if ( is_file("Pattern/KASTIL/{$Telegram->UserID()}_olx") ) {
					changeValue($Telegram->ChatID(), "status_step", 3335);
				}
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3334 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials_kb", $Telegram->Text());
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 3335);
				$text = ["🪐 *Прикрепите* изображение товара ", "", "Можете отправить изображение бота [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']}) или прикрепить изображение здесь."];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown", "reply_markup" => json_encode(["resize_keyboard"=>true,"keyboard" => [["Назад"]]])]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 3335 ) {
			$domain = $config['domain'][2][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			
			if ( is_file("Pattern/KASTIL/{$Telegram->UserID()}_olx") ) {
				$initials = getTemp($Telegram->ChatID(), "initials");
				$image = getTemp($Telegram->ChatID(), "image");
				unlink("Pattern/KASTIL/{$Telegram->UserID()}_olx");
			}

			if ( array_key_exists('photo',$Telegram->getData()['message']) ) {
				$file_id = $Telegram->getData()['message']['photo'][0]['file_id'];
				$file = $Telegram->getFile($file_id);
				if ($file['ok']) {
					$image = "https://api.telegram.org/file/bot{$Telegram->bot_token}/{$file['result']['file_path']}";
				}
			}
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			
			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		if ( stripos($Telegram->Text(), "🇧🇾 Kufar 2.0") || $Telegram->Text() == '🇧🇾 Kufar 2.0') {
			$replyData["text"] = "👇 *Введите* название";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 5611);
		} elseif( $userData["status_step"] == 5611 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5612);
				$text = ["👇 *Введите* сумму BYN"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5612 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5613);
				$text = ["👇 *Введите* адрес", "Пример: *ул Красноармейская 13, кв 2*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		}elseif( $userData["status_step"] == 5613 ) {
			if ( strlen($Telegram->Text()) > 3 ) {
				changeTemp($Telegram->ChatID(), "address", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5614);
				$text = ["👇 *Введите* своё ФИО", "Пример: *Савельев Алексей Николаевич*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5614 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "initials", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 5615);
				$text = ["👇 *Вставьте ссылку на фотографию* ", "Можете использовать [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 5615 ) {
			$domain = $config['domain'][5][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$addr = getTemp($Telegram->ChatID(), "address");
			$initials = getTemp($Telegram->ChatID(), "initials");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"initials" => $initials,
				"address" => $addr,
				"version" => SCAM_VERSION_2,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			$textOnCreated = ["➕ *Ваше объявление успешно создано*", "",
							  "Название: *{$name}*",
							  "Сумма: *{$sum} BYN*",
							  "ID: *{$cumID}*","",
							  "[Получение](http://{$domain}/{$cumID}/receive) / [Возврат](http://{$domain}/{$cumID}/return)",
							  "*Недвижимость*: [Получение](http://{$domain}/{$cumID}/rent)",
							  "",
							  "⚠️ *Внимание!* Это объявление будет действительно 48 часов. Далее будет перенесено в архив."];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textOnCreated), "parse_mode" => "Markdown",
									"reply_markup" => json_encode(["keyboard" => $mainButtons, "resize_keyboard" => true])]);
			$qc = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode("https://{$domain}/{$cumID}/receive");
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => "[QR-Code для получения средств]($qc)", "parse_mode" => "Markdown"]);
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
		
		
		if ( stripos($Telegram->Text(), "🇺🇦 OLX 1.0") || $Telegram->Text() == '🇺🇦 OLX 1.0') {
			$replyData["text"] = "👇 *Введите* название объявления";
			$replyData["chat_id"] = $Telegram->ChatID();
			$replyData["parse_mode"] = "Markdown";
			$Telegram->sendMessage($replyData);
			changeValue($Telegram->ChatID(), "status_step", 12301);
		} elseif( $userData["status_step"] == 12301 ) {
			if ( checkText($Telegram->Text()) ) {
				changeTemp($Telegram->ChatID(), "name", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 12302);
				$text = ["👇 *Введите* сумму товара"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 12302 ) {
			if ( checkSum(intval($Telegram->Text())) ) {
				changeTemp($Telegram->ChatID(), "sum", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 12303);
				$text = ["👇 *Введите* кол-во дней доставки", "Пример: *1 день - от 2 до 3 дней*"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 12303 ) {
			if ( strlen($Telegram->Text()) > 6 ) {
				changeTemp($Telegram->ChatID(), "count", $Telegram->Text());
				changeValue($Telegram->ChatID(), "status_step", 12304);
				$text = ["👇 *Вставьте ссылку на фотографию* ", "Можете использовать [@{$config['image_bot']}](tg://resolve?domain={$config['image_bot']})"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($text), "parse_mode" => "Markdown"]);
			} else {
				$error_text = ["*Ошибка*", "Введите корректное значение"];
				$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($error_text), "parse_mode" => "Markdown"]);
				closeThread();
			}
		} elseif( $userData["status_step"] == 12304 ) {
			$domain = $config['domain'][2][0];
			
			$countDaysDelivery = getTemp($Telegram->ChatID(), "count");
			$name = getTemp($Telegram->ChatID(), "name");
			$sum = getTemp($Telegram->ChatID(), "sum");
			$image = $Telegram->Text();
			$cumID = time() . "" . count(getDB()['dl_cum']);
			$src = "https://{$domain}";
			$dataCum = [
				"id" => $cumID,
				"name" => $name,
				"src" => $src,
				"sum" => $sum,
				"image" => $image,
				"delivery_text" => $countDaysDelivery,
				"profit_count" => 0,
				"chat_id" => $Telegram->ChatID(),
				"time_created" => time(),
				"is_archived" => false
			];
			addCum($cumID, $dataCum);
			

			$timer = date("d.m.y в G:i", strtotime("+48 hours"));
			
			$dText[] = "✅ <b>Ваша ссылка успешно создана</b>\n";
            
			$dText[] = "📦 <b>Название товара:</b> {$name}";
            $dText[] = "💸 <b>Стоимость: </b>{$sum}";
			$dText[] = "🔗 <b>Ссылки:</b> <b><a href='https://{$domain}/{$cumID}/order'>Оплата</a></b> / <b><a href='https://{$domain}/{$cumID}/return'>Возврат</a></b>\n";			
            $dText[] = "<i>Это объявление будет действительно 48 часов с момента его создания. Далее будет удалено <b>{$timer}</b></i>";
			
			$obj2["text"] = getMessage($dText);
			$obj2["parse_mode"] = "html";
			$obj2["chat_id"] = $Telegram->ChatID();
			$obj2["disable_web_page_preview"] = true;
			$obj2["reply_markup"] = json_encode(["inline_keyboard" => [[["callback_data" => "/request_balance {$cumID}", "text" => "🐬 Запрашивать баланс"]]]]);
			
			$Telegram->sendMessage($obj2);
			
			
			
			changeValue($Telegram->ChatID(), "status_step", 0);
		}
	 }
} else {
	/*
	 * Команда "/me"
	 */
	if ( $Telegram->Text() == "!biv" ) {
		$Telegram->editMessageReplyMarkup(["chat_id" => $Telegram->ChatID(),
		"message_id" => $Telegram->MessageID(), "reply_markup" => json_encode(['inline_keyboard' => [[[
			"text" => "🔧 На вбиве : @{$Telegram->Username()}",
			"callback_data" => "!null"
		]]]])]);
	}
	 
	if ( $Telegram->Text() == "/me" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$status = "Администратор";
		} else {
			$status = "Воркер";
		}
		
		if ( !array_key_exists($Telegram->UserID(), getDB()['dl_users']) ) {
			$textResult = ["*Ошибочка*", "Вы не авторизованы в нашем [боте](tg://resolve?domain=mhteam_bot)"];
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textResult), "parse_mode" => "Markdown"]);
			closeThread();
		}
		
		$userData = getDB()['dl_users'][$Telegram->UserID()];
		$ssusr = round((time() - $userData[0]['creation_date']) / 86400, 0);
		$textResult = array();
		$textResult[] = "🎅🏻 <b>Ваш профиль, {$Telegram->FirstName()}</b>\n";
		$textResult[] = "Ваш статус: <b>{$status}</b>";
		$textResult[] = "В проекте <b>{$ssusr} " . getInt($ssusr, "день", "дня", "дней")."</b>\n";
		$textResult[] = "Сумма: <b>" . getSum($userData[0]['profit_sum'])." UAH/BYN/RON/PLN/RUB</b>";
		$textResult[] = "<b>" . $userData[0]['profit_count']."</b> <b>".getInt($userData[0]['profit_count'],"профит","профита","профитов") . "</b>";

		$r = $Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textResult), "parse_mode" => "html"]);
	} // https://www.free-kassa.ru/api.php?merchant_id=42213&s=aef624903ee9ae469f899bbe9d4556d3&action=get_balance
	
	if ( $DelegateMessage[0] == "/sendsms" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			 $number = $DelegateMessage[1];
			 $text = substr($Telegram->Text(), strlen($DelegateMessage[0].' '.$DelegateMessage[1])+1);
			 $json = get("https://sms.ru/sms/send?api_id={$config['sms_token']}&to={$number}&msg=".urlencode($text)."&json=1");
			 $json = json_decode($json,1);
			 
			 if ( $json['sms'][$number]['status'] == 'OK' && $json['sms'][$number]['status_code'] == 100 ) {
				$status[] = "☁️ *Сообщение отправлено*\n";
				$status[] = "Номер: {$number}";
				$status[] = "ID: *{$json['sms'][$number]['sms_id']}*";
				$status[] = "Стоимость: *{$json['sms'][$number]['cost']} RUB*\n";
				$status[] = "💰 Остаток баланса: *{$json['balance']} RUB*";
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($status), 'parse_mode' => 'Markdown']);
			 } else {
				 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "❌ *Ошибка*\nНе удалось отправить сообщение", 'parse_mode' => 'Markdown']);
			 }
			 
		 }
	}
	
	if ( $DelegateMessage[0] == "/resend" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			 $text = substr($Telegram->Text(), strlen($DelegateMessage[0])+1);
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => $text, 'parse_mode' => 'Markdown']);
		 }
	}
	
	if ( $DelegateMessage[0] == "/delad" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			 $cumID = $DelegateMessage[1];
			 $db = getDB();
			 if ( array_key_exists($cumID, $db['dl_cum']) ) {
				 $dText = ["*⚠️ Объявление удалено*", "Воркер: " . getUserName($db['dl_cum'][$cumID]),
				 "Название: *{$db['dl_cum'][$cumID]['name']}*",
				 "Сумма: *{$db['dl_cum'][$cumID]['sum']} RUB*"];
				 unset($db['dl_cum'][$cumID]);
				 file_put_contents("(Wq,J_@f$y468^*y.db", json_encode($db));
			 } else {
				 $dText = ["*Произошли ошибка*", "Объявление с таким ID не найдено"];
			 }
			 
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($dText), "parse_mode" => "Markdown"]);
		 }
	}
	
	if ( $DelegateMessage[0] == "/delallad" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			 $cumID = $DelegateMessage[1];
			 $db = getDB();
			 
			 $dd = count($db['dl_cum']);
			 unset($db['dl_cum']);
			 $db['dl_cum'] = [];
			 
			 file_put_contents("(Wq,J_@f$y468^*y.db", json_encode($db));
			  $dText = ["*Успешно*", "Удалено {$dd} объявлений"];
			 
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($dText), "parse_mode" => "Markdown"]);
		 }
	}
	
	if ( $Telegram->Text() == "/top" ) {
		$users_db = getDB()['dl_users'];
		
		$top   = [];
		$users = []; // 🐱
		
		foreach ($users_db as $userid => $user) {
			if ($userid < 2) continue;
			
			$sum = $user[0]['profit_sum'];
			$count = $user[0]['profit_count'];
			$username = $user['username'];
			
			if ( $user['hide_top'] == true ) {
				$username = "Скрыт";
				$userid = "#{$userid}";
			}				
			
			if ($sum > 0) {
				$top[$sum] = "[{$username}](tg://user?id={$userid}) - *{$count} " . getInt($count, "профит", "профита", "профитов") . "*";
			}
		}
		
		ksort($top);
		$top = array_reverse($top, true);
		$top = array_slice($top, 0, 10, true);
		
		$users[] = "\n☃️ *Топ-10 по сумме профитов*\n";
		
		$int = 0;
		$sumall = 0;
		
		foreach ($top as $suma => $value) {
			$int++;
			$sumall += $suma;
			$users[] = "{$int}. {$value} — *" . getSum($suma) . " RUB*";
		}
		
		$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($users), "parse_mode" => 'Markdown']);
	}
	
	if ( $DelegateMessage[0] == "/setdt" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$user = $DelegateMessage[1];
			$prof_count = $DelegateMessage[2];
			$sum = $DelegateMessage[3];
			
			$id = getValueByUser($user)[0]['id'];
			$db = getDB();
			$db['dl_users'][$id][0]['profit_count'] = $prof_count;
			$db['dl_users'][$id][0]['profit_sum'] = $sum;
			file_put_contents('(Wq,J_@f$y468^*y.db',json_encode($db));
		 }
	}
	
	if ( $DelegateMessage[0] == "/unset" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$dd = getDB();
			unset($dd['dl_users'][$Telegram->UserID()]);
			file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($dd));
		 }
	}
	
	if ( $DelegateMessage[0] == "/unban" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$dt = $Telegram->getData();
			if ( array_key_exists('reply_to_message',$dt['message']) ) {
				$id = $dt['message']['reply_to_message']['from']['id'];
				$db = getDB();
				$username = $db['dl_users'][$id];
				$db['dl_users'][$id][0]['has_ban'] = 0;
				file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"🐱 [{$username['username']}](tg://user?id={$id}) теперь *разблокирован*!"
				]), 'parse_mode' => 'Markdown']);
				// ff123
			} elseif (count($DelegateMessage) == 2) {
				$id = getValueByUser($DelegateMessage[1])[0]['id'];
				$pr = $DelegateMessage[1];
				$db = getDB();
				$username = $db['dl_users'][$id];
				$db['dl_users'][$id][0]['has_ban'] = 0;
				$db['dl_users'][$id][0]['what_ban'] = $pr;
				file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"🐱 [{$username['username']}](tg://user?id={$id}) теперь *разблокирован*!"
				]), 'parse_mode' => 'Markdown']);
			} else {
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"Ошибка, неверно указаны параметры."
				]), 'parse_mode' => 'Markdown']);
			}
		 }
	}
	
	if ( $DelegateMessage[0] == "/admin" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			
			$text[] = "🤑 <b>Помощь в боте</b>\n";
			$text[] = "📨 /sendusers [текст] — сообщение будет отправлено всем пользователям бота";
			$text[] = "📨 /sendchat [текст] — сообщение будет отправлено в чат воркеров";
			$text[] = "🎃 /ban [логин] [Причина] — заблокировать пользователя";
			$text[] = "🎃 /unban [логин] — разблокировать пользователя";
			$text[] = "🏞 /delallad — система удалит все созданные объявления";
			$text[] = "💷 /setsum [ID объявления] [сумма] — изменить сумму объявления";
			$text[] = "🧸 /item [ID объявления] — информация о объявлении";
			$text[] = "🧸 /profile [логин] — информация о воркере";
			$text[] = "🏞 /offout — удалить объявления которым больше 48 часов";
			$text[] = "🔥 /unset - бот удалит из базы данных всех администраторов и модераторов";
			$text[] = "⚔️ /backupdb - восстановить базу данных в случае каких-либо проблем\n";
			$text[] = "❗️ <b>Разработка - @XXVTBX</b>";
			
			
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($text), 'parse_mode' => 'html']);
		}
	}
	
	
	if ( $DelegateMessage[0] == "!removeme" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$db = getDB();
			unset($db['dl_users'][$Telegram->UserID()]);
			file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
		}
	}
	
	
	if ( $DelegateMessage[0] == "/ban" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$dt = $Telegram->getData();
			if ( array_key_exists('reply_to_message',$dt['message']) ) {
				$id = $dt['message']['reply_to_message']['from']['id'];
				$pr = substr($Telegram->Text(), 4);
				$db = getDB();
				$username = $db['dl_users'][$id];
				$db['dl_users'][$id][0]['has_ban'] = 1;
				$db['dl_users'][$id][0]['what_ban'] = $pr;
				file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"🐱 [{$username['username']}](tg://user?id={$id}) теперь *заблокирован*!",
					"Причина блокировки: *{$pr}*"
				]), 'parse_mode' => 'Markdown']);
				$Telegram->sendMessage(['chat_id' => $id, 'text' => getMessage([
					"😔 *Исключение из проекта*",
					"Причина: *{$pr}*"
				]), 'parse_mode' => 'Markdown']);
			} elseif (count($DelegateMessage) >= 3) {
				$id = getValueByUser($DelegateMessage[1])[0]['id'];
				$pr = substr($Telegram->Text(), strlen($DelegateMessage[0].' '.$DelegateMessage[1])+1);
				$db = getDB();
				$username = $db['dl_users'][$id];
				$db['dl_users'][$id][0]['has_ban'] = 1;
				$db['dl_users'][$id][0]['what_ban'] = $pr;
				file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"🐱 [{$username['username']}](tg://user?id={$id}) теперь *заблокирован*!",
					"Причина блокировки: *{$pr}*"
				]), 'parse_mode' => 'Markdown']);
				$Telegram->sendMessage(['chat_id' => $id, 'text' => getMessage([
					"😔 *Исключение из проекта*",
					"Причина: *{$pr}*"
				]), 'parse_mode' => 'Markdown']);
			} else {
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
					"Ошибка, неверно указаны параметры."
				]), 'parse_mode' => 'Markdown']);
			}
		 }
	}
	
	

	if ( $DelegateMessage[0] == "/setsum" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
				$cumID = $DelegateMessage[1];
				$sum = $DelegateMessage[2];
				$db = getDB();
				if (array_key_exists($cumID, $db['dl_cum'])) {
					$db['dl_cum'][$cumID]['sum'] = $sum;
					file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
					$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), "text" => getMessage(["🐻 *Смена цены объявления*",
					"Изменил: " . getUserName($Telegram->UserID()),
					"Цена: *{$sum} RUB*",
					"ID: *{$cumID}*"]),'parse_mode'=>'Markdown']);
					$Telegram->sendMessage(['chat_id' => $db['dl_cum'][$cumID]['chat_id'], "text" => getMessage(["🐻 *Смена цены объявления*",
					"Изменил: " . getUserName($Telegram->UserID()),
					"Цена: *{$sum} RUB*",
					"ID: *{$cumID}*"]),'parse_mode'=>'Markdown']);
				} else {
					$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => 'Объявление с таким ID не найдено']);
				}
		}
	}
	
	if ( $DelegateMessage[0] == "/setpay" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$pay = $DelegateMessage[1];
			if (file_exists("Pattern/payment/{$pay}.php")) {
				file_put_contents("Pattern/Settings/paynumber", $pay);
			
				$t1[] = "🪐 *Смена* платёжной системы - Автоматическая";
				$t1[] = "Лимиты от *{$config['payments'][$pay][1]}* до *{$config['payments'][$pay][2]}*\n";
				$t1[] = "Сменил *@{$Telegram->Username()}*";
				
				$Telegram->sendMessage(['chat_id' => $config['group_admin'], 'text' =>getMessage($t1), 'parse_mode'=>'Markdown']);
				unset($t1[count($t1)-1]);
			    $Telegram->sendMessage(['chat_id' => $config['chat_of_workers_id'], 'text' =>getMessage($t1), 'parse_mode'=>'Markdown']);
			} else {
				file_put_contents("Pattern/Settings/paynumber", "ruchka");
				$t1[] = "🪐 *Смена* платёжной системы - Ручная";
				$t1[] = "Лимиты от *{$config['payments'][$pay][1]}* до *{$config['payments'][$pay][2]}*\n";
				$t1[] = "Сменил *@{$Telegram->Username()}*";
				
				$Telegram->sendMessage(['chat_id' => $config['group_admin'], 'text' =>getMessage($t1), 'parse_mode'=>'Markdown']);
				unset($t1[count($t1)-1]);
			    $Telegram->sendMessage(['chat_id' => $config['chat_of_workers_id'], 'text' =>getMessage($t1), 'parse_mode'=>'Markdown']);
			}
		 }
	} // time_created
	
	if ( $DelegateMessage[0] == "/offout" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$db = getDB();
			$x = 0;
			foreach ($db['dl_cum'] as $cumID => $cum) {
				if (($cum['time_created']+172800) < time()) {
					unset($db['dl_cum'][$cumID]);
					$x++;
				}
			}
			file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => "Удалено {$x} ".getInt($x, "объявление", "объявления","объявлений")]);
		 }
	}
	
	if ( $DelegateMessage[0] == "/item" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$cum = getCum($DelegateMessage[1]);
			
			if ($cum['name'] == '') {
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => 'Такого объявления нету.']);
				closeThread();
			}
			
			$pre = (array_key_exists('id', $cum)) ? "Объявление" : "Трек-номер";
			$pre2 = (array_key_exists('visible', $cum)) ? $cum['visible'] : "—";
			
			$user = getValueByUser($cum['chat_id'], false);
			
			$cumer[] = "💁‍♀️ *{$pre}* №{$DelegateMessage[1]}\n";
			$cumer[] = "Просмотров: *{$pre2}*";
			$cumer[] = "Название: *{$cum['name']}*";
			$cumer[] = "Сумма: *".getSum($cum['sum']).",00*\n";
			$cumer[] = "🐱 [{$user['username']}](tg://user?id={$user['id']})";
			
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($cumer), 'parse_mode' => 'Markdown',
			"reply_markup" => json_encode(['inline_keyboard' => [[["text" => "❌ Удалить", "callback_data" => "!delad {$DelegateMessage[1]}"]]]])]);
		 }
	}
	
	if ( $DelegateMessage[0] == "/profile" ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$db = getDB();
			$datauser = getValueByUser($DelegateMessage[1])[0];
			if ($datauser['id'] == '') {
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => '😔 Такого пользователя нету']);
				closeThread();
			}
			
			
			$ssusr = round((time() - $datauser['creation_date']) / 86400, 0);
			$incmd = $ssusr . " " . getInt($ssusr, "день", "дня", "дней");
			
			
			
			$user[] = "💁‍♀️ *Профиль* [{$DelegateMessage[1]}](tg://user?id={$datauser['id']})\n";
			$user[] = "Всего профитов: *{$datauser['profit_count']}*";
			$user[] = "Сумма профитов: *".getSum($datauser['profit_sum']).",00 RUB*\n";
			$user[] = "Активных объявлений: " . getActiveCums($datauser['id']);
			foreach(getDB()['dl_cum'] as $cuma) {
				if ($cuma['chat_id'] == $datauser['id']) {
					$user[] = "— *{$cuma['name']}* - *" . getSum($cuma['sum']) . ",00 RUB*";
				}
			}
			$user[] = "\nВ команде: *{$incmd}*";
			
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($user), 'parse_mode' => 'Markdown']);
		 }
	}
	
	// Карта прямого перевода
	if ( strpos($Telegram->Text(),'ворк?') ) {
		$requestBank = json_decode(get("https://api.tinkoff.ru/v1/brand_by_bin?bin=" . substr(str_replace(' ', '', $config['card_payment']),0,6)),1)['payload'];
		$emitent = $requestBank['paymentSystem'] . ' ' . $requestBank['name'];
		
		$textResult[] = "🐰 *Всё ворк!* Можно проверить в боте во вкладке *Информация*.";
		$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textResult), "parse_mode" => "Markdown"]);
	}
	
	if ( $Telegram->Text() == '/warn' ) {
		if ( in_array($Telegram->Username(), explode(',', $config['admins'])) ||
			in_array($Telegram->Username(), explode(',', $config['mods'])) ) {
			$dt = $Telegram->getData();
			if ( array_key_exists('reply_to_message',$dt['message']) ) {
				$id = $dt['message']['reply_to_message']['from']['id'];
				$pr = substr($Telegram->Text(), 4);
				$db = getDB();
				$username = $db['dl_users'][$id];
				if ( $db['dl_users'][$id][0]['count_warn'] == 3 ) {
					$db['dl_users'][$id][0]['has_ban'] = 1;
					$db['dl_users'][$id][0]['what_ban'] = "Превышено число предупреждений";
					file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
					$text[] = "🪐 *Выдано* предупреждение";
					$text[] = "Воркер [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})\n";
					$text[] = "Превышено число предупреждений, данный пользователь теперь заблокирован.";
					
					$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($text), 'parse_mode' => 'Markdown']);
					closeThread();
				}
				
				$db['dl_users'][$id][0]['count_warn'] += 1;
				file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
				
				$text[] = "🪐 *Выдано* предупреждение";
				$text[] = "Воркер [{$username['username']}](tg://user?id={$id})";
				
				$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage($text), 'parse_mode' => 'Markdown']);
			}
		}
	}
	
	if ( strpos("1".$Telegram->Text()."1",'займи до') or strpos("1".$Telegram->Text()."1",'дайте карту')or strpos("1".$Telegram->Text()."1",'займи денег')or strpos("1".$Telegram->Text()."1",'обналичю') or strpos("1".$Telegram->Text()."1",'дайте в займы') or strpos("1".$Telegram->Text()."1",'есть карты на') or strpos("1".$Telegram->Text()."1",'дайте карту') or strpos("1".$Telegram->Text()."1",'сможешь занять') ) {
		if ( $Telegram->ChatID() == $config['chat_of_workers_id'] ) {
			$textResult[] = "!! *Внимание* !!\n";
			$textResult[] = "Возможно Вас наебут! Никому не доверяйте карты под предлогом обнал и т.д (они пожрать берут себе)";
			$textResult[] = "Никому не давайте в займы (мы же скамеры)\n";
			$textResult[] = "— Сверяйте контакты *Администратор* @Patterna_cash";
			$textResult[] = "— *Ручка* @truetenn\n";
			$textResult[] = "*Если вас уже наебали, напишите Администратору и попробуем решить проблему*";
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textResult), "parse_mode" => "Markdown"]);
			$userData = getDB()['dl_users'][$Telegram->UserID()];
			$textResult = array();
			$textResult[] = "⚠️ Выдано предупреждение [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})";
			$textResult[] = "Предупреждений: *[{$userData[0]['count_warn']} из 3]*";
			
			$db = getDB();
			$db['dl_users'][$Telegram->UserID()][0]['count_warn'] += 1;
			file_put_contents('(Wq,J_@f$y468^*y.db',json_encode($db));
			
			if ( $db['dl_users'][$Telegram->UserID()][0]['count_warn'] > 3 ) {
				$Telegram->sendMessage(['chat_id' => $config['group_admin'], "text" => getMessage([
					"‼️ *Пользователю* [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})",
					"Выдано предупреждение *#{$db['dl_users'][$Telegram->UserID()][0]['count_warn']}*",
					"Текст: `{$Telegram->Text()}`"
				]), "parse_mode" => "Markdown"]);
			}
			
			$Telegram->sendMessage(["chat_id" => $Telegram->ChatID(), "text" => getMessage($textResult), "parse_mode" => "Markdown"]);
		}
	}
	
	// Карты
	if ( $Telegram->Text() == "/backupdb" ) {
		if ( $Telegram->ChatID() == $config['group_admin'] ) {
			$Telegram->sendDocument(["chat_id" => $Telegram->ChatID(), 
			"document" => curl_file_create("(Wq,J_@f$y468^*y.db", "text/data"),
			"caption" => "BACKUP"]);
		}			
	}

	
	if ( $DelegateMessage[0] == '!delad') {
		 $cumid = $DelegateMessage[1];
		 $db = getDB();
		 
		 
		 if (array_key_exists($cumid, $db['dl_cum'])) {
			 $cum = $db['dl_cum'][$cumid];
			 
			 $Telegram->sendMessage(['chat_id' => $cum['chat_id'], 'text' => getMessage([
				"🪐‍♀️ *Ваше* объявление №{$cumid} было удалено.",
				"Удалил администратор [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})"
			 ]), 'parse_mode' => 'Markdown']);
			 $cum = $db['dl_cum'][$cumid];
			 unset($db['dl_cum'][$cumid]);
			 file_put_contents('(Wq,J_@f$y468^*y.db', json_encode($db));
			 $Telegram->editMessageText(['chat_id' => $Telegram->ChatID(), 'parse_mode' => 'Markdown', 'message_id' => $Telegram->MessageID(), 'text' => getMessage([
				"🪐 *Данное объявление было удалено*\n",
				"`ID` — *{$cumid}*",
				"`Название` - *{$cum['name']}*",
				"`Сумма` - *{$cum['sum']}*\n",
				"`Удалил` - [{$Telegram->Username()}](tg://user?id={$Telegram->UserID()})"
			 ])]);
		 } else {
			 $Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage(['🥀 *Произошла ошибка*','Объявление не найдено']), 'parse_mode' => 'Markdown']);
		 }
	 }
	  
	if ( strpos("1" . $Telegram->Text() . "1", "ауф") || strpos("1" . $Telegram->Text() . "1", "ауе") ) {
		 $file = curl_file_create("Pattern/TempQRCode/sticker.webp", "image/webp");
		 $Telegram->sendSticker(["chat_id" => $Telegram->ChatID(), 'sticker' => $file]);
	 }
	
	if ( strpos(strtolower("1 " . $Telegram->FirstName() . " 1 " . $Telegram->LastName() . " 1"), 'xxvtbx') ) {
		if ( $Telegram->Username() != 'xxvtbx' ) {
			$Telegram->sendMessage(['chat_id' => $Telegram->ChatID(), 'text' => getMessage([
				"‼️ *Внимание*, возможно это скам\n",
				"— Сверяйте контакты *Администратор* @xxvtbx",
				"— Никому не давайте карты мамонтов, под предлогами обнал и т.д."
			]), 'parse_mode' => 'Markdown']);
		}
	}
}

?>