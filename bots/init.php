<?php

include('connect.php');
file_get_contents($lazy_api . 'setWebhook?url=https://ktrade.kz/libs/bots/lazy.php');
file_get_contents($auto_api . 'setWebhook?url=https://ktrade.kz/libs/bots/auto.php');
file_get_contents($cons_api . 'setWebhook?url=https://ktrade.kz/libs/bots/cons.php');
file_get_contents($ktrade_api . 'setWebhook?url=https://ktrade.kz/libs/bots/ktrade.php');
echo 'ok';
