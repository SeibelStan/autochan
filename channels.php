<?php include('includes/header.php') ?>
<?php
$chans = dbs("select * from autochan_chans where user_id = '$user->user_id' order by name");
?>
<div id="channels" class="wrapper">
	<div id="newpost" class="container chan-add">
		<div class="three-column">
			<div class="column"></div>
			<div class="column">
				<div class="title">
					<h2>Новый канал</h2>
				</div>
				<form action="logic.php?act=chan-add" method="post">
					<input type="text" class="text" name="name" placeholder="@названиеканала" pattern="@.+">
					<button type="submit" class="button button-small">Добавить канал</button>
				</form>
			</div>
			<div class="column"></div>
		</div>
	</div>
	<div id="postpans" class="container">
		<div class="title">
			<h2>Каналы</h2>
		</div>
		<div id="portfolio" class="container four-column">
			<?php foreach($chans as $chan) : ?>
				<div class="column">
					<div class="box">
						<p><a href="https://telegram.me/<?= preg_replace('/@/', '', $chan->name) ?>"><?= $chan->name ?></a></p>
						<div class="box-buttons">
							<a class="button button-small" href="logic.php?act=chan-remove&id=<?= $chan->id ?>">Убрать</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php include('includes/footer.php') ?>
