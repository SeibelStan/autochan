<?php

ini_set('display_errors', 1);
ini_set('track_errors', 1);
ini_set('html_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Moscow');

define('DOMAIN', 'https://seibel.xyz/');

function request($name) {
	return isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
}

function session($name, $value = null) {
    if(isset($value)) {
        $_SESSION[$name] = $value;
    }
	return isset($_SESSION[$name]) ? $_SESSION[$name] : '';
}

function redirect($path) {
	header('Location: ' . $path);
}

function back() {
	redirect($_SERVER['HTTP_REFERER']);
}

function abort($err) {
	redirect('?ctrl=errors&code=' . $err);
}

function dbs($sql) {
	require('connect.php');
	$result = $db->query($sql);
	$data = [];
	if($result->num_rows) {
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$data[] = (object)$row;
		}
	}
	return $data;
}

function dbi($sql) {
	require('connect.php');
	$db->query($sql);
	return $db->insert_id;
}

function dbu($sql) {
	require('connect.php');
	$db->query($sql);
	return $db->affected_rows;
}

function img_resize($src, $dest, $mw, $height, $rgb = 0xFFFFFF, $quality = 95) {
	if(!file_exists($src)) {
		return false;
	}

	$size = getimagesize($src);

	if($size === false) {
		return false;
	}

	$format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
	$icfunc = "imagecreatefrom" . $format;
	if(!function_exists($icfunc)) return false;


	$isrc = $icfunc($src);
	$idest = imagecreatetruecolor($mw, $height);

	imagefill($idest, 0, 0, $rgb);
	imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $mw, $height, $size[0], $size[1]);

	imagejpeg($idest, $dest, $quality);

	imagedestroy($isrc);
	imagedestroy($idest);

	return true;

}

function img_do_resize($src, $mw = 1000) {
	$size = getimagesize($src);
	if($size[0] > $mw or $size[1] > $mw) {
		$height = $size[1] * $mw / $size[0];
		img_resize($src, $src, $mw, $height);
	}
}

include('../bots/libs/Telegram.php');
include('../bots/libs/TelegramMethods.php');

$auto_token = '191034505:AAEfwwCUlFJycgBIgRcVM1nyhfrllb4qnuU';
$auto_api = 'https://api.telegram.org/bot' . $auto_token . '/';
$auto = new Telegram($auto_token);

function checkOwner($chat, $user_id, $bot) {
	$admins = $bot->getChatAdministrators([
		'chat_id' => $chat
	]);
	$owner = false;
	foreach($admins as $admin) {
		if($admin['user']['id'] = $user_id) {
			$owner = true;
		}
	}
	return $owner;
}

require('connect.php');
session_start();
$user = session('user');
