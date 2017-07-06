<?php

ini_set('display_errors', 1);
ini_set('track_errors', 1);
ini_set('html_errors', 1);
error_reporting(E_ALL);

include('libs/Telegram.php');
include('libs/TelegramMethods.php');

$db = new mysqli('localhost', 'root', 'xxx');
$db->select_db('u852694992_db2');
$db->query("SET NAMES utf8");

$auto_token = 'telegram:token';
$auto_api = 'https://api.telegram.org/bot' . $auto_token . '/';
$auto = new Telegram($auto_token);

$myid = 53540040;