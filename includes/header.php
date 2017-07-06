<?php
require('core.php');
$code = rand(10000, 99999);
if(!$user) {
	if(!preg_match('/index/', $_SERVER['PHP_SELF'])) {
		redirect('index.php');
	}
}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Autochan</title>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="keywords" content="" />
	<meta name="description" content="Сервис составления очередей для каналов Telegram" />
	<link href="css/default.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/fonts.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="js/core.js"></script>
	<script>
		var code = '<?= $code ?>';
	</script>
</head>
<body>
<header id="header-wrapper">
	<div id="header" class="container">
		<div id="logo">
			<h1><a href="index.php"><span class="icon icon-cog"></span>Autochan</a></h1>
			<div id="menu">
				<ul>
					<?php if(!$user) : ?>
						<li><a href="https://telegram.me/AutoChanBot?start=<?= $code ?>" target="_blank" id="auth-link">Авторизация</a>
					<?php else : ?>
						<li><a href="workflow.php"><?= $user->first_name ?></a>
						<li><a href="channels.php">Мои каналы</a>
						<li><a href="logic.php?act=logout">Выход</a>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</header>
<div id="notify" style="display: none;">
	<div id="notify-message" class="container"></div>
</div>
