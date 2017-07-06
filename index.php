<?php include('includes/header.php') ?>
<?php
$posts = dbs("select * from autochan where image <> '' and content <> '' order by id desc limit 8");
?>
<div id="page-wrapper">
	<div id="page" class="container">
		<div class="title">
			<h2>Приветствуем на нашем сервисе!</h2>
		</div>
		<p>Это <strong>Autochan</strong> &mdash; сервис составления очередей для каналов Telegram.</p>
	</div>
</div>
<div class="wrapper">
	<div id="three-column" class="container three-column">
		<div class="column">
			<div class="title">
				<h2>Быстро</h2>
			</div>
			<p>Авторизайтесь через нашего бота и начинайте составлять рассписания записей.</p>
		</div>
		<div class="column">
			<div class="title">
				<h2>Просто</h2>
			</div>
			<p>Если у вас возникнут затруднения, мы вам поможем. Так же, можем обсудить изменения и нововведения.</p>
		</div>
		<div class="column">
			<div class="title">
				<h2>Бесплатно</h2>
			</div>
			<p>Вы можете просто пользоваться сервисом. На время теста &mdash; ограничений и оплаты нет.</p>
		</div>
	</div>
	<div id="portfolio" class="container four-column">
		<?php foreach($posts as $post) : ?>
			<div class="column">
				<div class="box">
					<img src="storage/<?= $post->image ?>" class="image image-full">
					<p><a href="https://telegram.me/<?= preg_replace('/@/', '', $post->chan) ?>"><?= $post->chan ?></a></p>
					<p data-markdown=""><?= mb_substr($post->content, 0, 100) ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php include('includes/footer.php') ?>
