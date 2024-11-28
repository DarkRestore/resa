<?php

include("Settings.php");
include("Functions.php");

$actual_link = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$field["url"] = dirname("https://{$actual_link}")."/Request.php?pwd={$config['request_bot_pwd']}";
$data = get("https://api.telegram.org/bot{$config['bot_token']}/setWebhook?url={$field["url"]}");

$data = json_decode($data, true);

if ( $data["result"] == true ) {
	exit("🎩 Поздравляю , бот успешно установлен");
}
?>