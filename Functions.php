<?php

function getMessage(array $text) {
	return implode(PHP_EOL, $text);
}

function isMail($mail) {
	$exploded = explode("@", $mail);
	$exploded2 = explode(".", $mail);
	if (count($exploded) == 2) {
		if ( strlen($exploded[0]) > 3 && strlen($exploded[1]) > 2 ) {
			return true;
		} else {
			return false;
		}
	} else return;
}

function parseAVITO($link) {
	$doc = new DomDocument();
	@$doc->loadHTML( file_get_contents($link) );
	$xpath = new DOMXPath($doc);
	$query = '//*/meta';
	$metas = $xpath->query($query);
	$rmetas = array();
	foreach ($metas as $meta) {
		$property = $meta->getAttribute('property');
		$content = $meta->getAttribute('content');
		if(!empty($property) && preg_match('#^og:#', $property)) {
			$rmetas[$property] = $content;
		}
	}

	$tags = get_meta_tags($link);
	return ["name" => $rmetas['og:title'], "image" => $rmetas['og:image'],
	"sum" => filter_var(explode(':',$tags['description'])[0], FILTER_SANITIZE_NUMBER_INT)];
}

function parseOLX($link) {
	$doc = new DomDocument();
	@$doc->loadHTML( file_get_contents($link) );
	$xpath = new DOMXPath($doc);
	$query = '//*/meta';
	$metas = $xpath->query($query);
	$rmetas = array();
	foreach ($metas as $meta) {
		$property = $meta->getAttribute('property');
		$content = $meta->getAttribute('content');
		if(!empty($property) && preg_match('#^og:#', $property)) {
			$rmetas[$property] = $content;
		}
	}

	$tags = get_meta_tags($link);
	return ["name" => $rmetas['og:title'], "image" => $rmetas['og:image'],
	"sum" => filter_var(explode(':',$tags['description'])[0], FILTER_SANITIZE_NUMBER_INT)];
}

function getSum($text) {
	return number_format($text, 0, ',', ' ');
}

function hideCard($cc, $a = true) {
	if ($a)
		return '**** **** **** '.str_replace(" ", "", substr($cc,-4));
	else return '**** '.str_replace(" ", "", substr($cc,-4));
}

function get($url) {
	$ch = curl_init($url);
	curl_setopt_array($ch, [
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
	]);
	return curl_exec($ch);
}

function checkSum($sum) {
	$sum = (int)$sum;
	return (is_int($sum) && $sum > 10 && $sum < 2000000);
}

function checkText($text) {
	return (strlen($text) > 3 && strlen($text) < 255);
}

function getCum($cumID) {
	$Values = getDB()['dl_cum'];
	if ( $Values[$cumID] != '' ) {
		return $Values[$cumID];
	} else {
		return false;
	}
}

function setCum($cumID, $index, $value) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$db = getDB();
	$db['dl_cum'][$cumID][$index] = $value;
	file_put_contents($databaseFile, json_encode($db));
}

function getServerStatus(array $domains) {
	$result = array();
	foreach ( $domains as $dmn ) {
		$result[] = [$dmn[1], fsockopen($dmn[0], 443)];
	}
	return $result;
}

function getValueByUser($value, $has_by_tag = true) {
	$Values = getDB()['dl_users'];
	if ( $has_by_tag ) {
		foreach ( $Values as $User ) {
			if ( $User['username'] == $value ) {
				return $User;
			}
		}
		return false;
	} else {
		if ( array_key_exists($value, $Values) ) {
			return $Values[$value];
		} else {
			return false;
		}
	}
}

function getUserName($user_id) {
	return "[".getValueByUser($user_id, false)['username']."](tg://user?id={$user_id})";
}

function checkUserExists($value, $has_by_tag = true) {
	$Values = getDB()['dl_users'];
	if ( $has_by_tag ) {
		foreach ( $Values as $User ) {
			if ( $User['username'] == $value ) {
				return true;
			}
		}
		return false;
	} else {
		return array_key_exists($value, $Values);
	}
}

function getDB() {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	return json_decode(file_get_contents($databaseFile), true);
}

function closeThread() {
	exit("<b>Connection disconnected!</b>");
}

function getActiveCums($chatID) {
	$cums = getDB()['dl_cum'];
	$count = 0;
	foreach ( $cums as $cum ) {
		if ( $cum['chat_id'] == $chatID )
			$count++;
	}
	return $count;
}

function checkMatch($text) {
	if ( strlen($text) < 3 ) {
		return [false, "Напишите подробнее"];
	}
	if ( strlen($text) > 100 ) {
		return [false, "Слишком много!"];
	}
	return [true, "Всё ок!"];
}

function addCum($CumID, $ValueItem) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$db = getDB();
	$db['dl_cum'][$CumID] = $ValueItem;
	file_put_contents($databaseFile, json_encode($db));
}

function changeTemp($chat_id, $item, $value) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$db = getDB();
	$db['dl_temp'][$chat_id][$item] = $value;
	file_put_contents($databaseFile, json_encode($db));
}

function getTemp($chat_id, $item) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$db = getDB();
	return $db['dl_temp'][$chat_id][$item];
}

function getTrackNumber() {
	$started = ["115","116"][mt_rand(0,1)];
	return $started . mt_rand(1111111, 9999999);
}

function setProfit($chat_id, $sum) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$db = getDB();
	$db['dl_users'][$chat_id][0]["profit_count"] += 1;
	$db['dl_users'][$chat_id][0]["profit_sum"] += $sum;
	file_put_contents($databaseFile, json_encode($db));
}

function changeValue($chat_id, $item, $value) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$db = getDB();
	$db['dl_users'][$chat_id][$item] = $value;
	file_put_contents($databaseFile, json_encode($db));
}

function getStatus($in) {
	switch ($in) {
		case 0: return "Воркер"; break;
		case 1: return "Топ воркер"; break;
		case 2: return "Модератор"; break;
		case 3: return "Администратор"; break;
		default: return "Неизвестно"; break;
	}
}

function getInt($n, $n1, $n2, $n5) {
    if($n >= 11 and $n <= 19) return $n5;
    $n = $n % 10;
    if($n == 1) return $n1;
    if($n >= 2 and $n <= 4) return $n2;
    return $n5;
  }

function getDays($int) {
	return round($int / 86400, 0);
}

function createUserInDB($Telegram, $ref = 0) {
	$databaseFile = "LA2fYTVi5e5tUCOB.db";
	$databaseTable = "dl_users";
	$queryData = [
		"id" => $Telegram->ChatID(),
		"username" => $Telegram->Username(),
		"first_name" => $Telegram->FirstName(),
		"last_name" => $Telegram->LastName(),
		"creation_date" => time(),
		"status_step" => 0,
		"status_in_group" => 0,
		"status_wait" => 0,
		"profit_count" => 0,
		"profit_sum" => 0,
		"has_warn" => 0,
		"count_warn" => 0,
		"stavka" => [0 => 80, 1 => 70], 
		"has_ban" => 0,
		"user_status" => 0,
		"receive_ban" => 0,
		"what_ban" => 0
	];
	if ( $ref != 0 ) $queryData['ref'] = $ref;
	$Values = getDB();
	$Values[$databaseTable][$Telegram->ChatID()][] = $queryData;
	file_put_contents($databaseFile, json_encode($Values));
}

?>