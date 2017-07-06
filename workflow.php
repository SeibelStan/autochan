<?php include('includes/header.php') ?>
<?php
$posts = dbs("select * from autochan where user_id = '$user->user_id' order by date asc, id desc");
$chans = dbs("select * from autochan_chans where user_id = '$user->user_id' order by name");
?>
<div id="workflow" class="wrapper">
	<div id="newpost" class="container">
		<div class="column">
			<div class="title">
				<h2>Новая запись</h2>
			</div>
			<form class="post-add" action="logic.php?act=post-add" method="post" enctype="multipart/form-data">
				<div class="three-column">
					<div class="column">
						<img src="images/image-add.png" class="image image-full" />
						<input type="file" name="image" class="image-inp" style="display: none;">
						<a class="button" id="post-add-image-remove" style="display: none;">Убрать</a>
					</div>
					<div class="column">
						<textarea type="text" name="content" id="post-add-content" placeholder="Текст"></textarea>
						<?php if($chans) : ?>
							<select name="chan">
								<?php foreach($chans as $chan) : ?>
									<option value="<?= $chan->name ?>"><?= $chan->name ?></option>
								<?php endforeach; ?>
							</select>
						<?php else : ?>
							<p><a href="channels.php">Добавьте каналы</a></p>
						<?php endif; ?>
						<input type="datetime-local" class="text" name="date">
						<p>Время сервера: <?= date('H:i') ?> (<?= date('P') ?>)</p>
						<button type="submit" class="button button-small">Добавить запись</button>
					</div>
					<div class="column">
						<div id="post-add-preview">
							Здесь будет появляться предпросмотр текста по мере набора.<br>
							Доступно форматирование MarkDown:<br>
							*жирный* _курсив_ <nobr>[текст ссылки](http://link.addr)</nobr> ```код```
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div id="postpans" class="container">
		<div class="title">
			<h2>Записи</h2>
		</div>
		<div id="portfolio" class="container four-column">
			<?php foreach($posts as $post) : ?>
				<form class="column" action="logic.php?act=post-save&id=<?= $post->id ?>" method="post">
					<div class="box">
						<?php if($post->image) : ?>
							<img src="storage/<?= $post->image ?>" class="image image-full">
						<?php endif; ?>
						<textarea type="text" <?= $post->image ? 'maxlength="200"' : '' ?> name="content" placeholder="Текст"><?= $post->content ?></textarea>
						<input type="hidden" name="image" value="<?= $post->image ?>">
						<select name="chan" id="post-<?= $post->id ?>-chan">
							<?php foreach($chans as $chan) : ?>
								<option value="<?= $chan->name ?>"><?= $chan->name ?></option>
							<?php endforeach; ?>
						</select>
						<script>
							$(function () {
								$('#post-<?= $post->id ?>-chan').find('option').each(function () {
									if($(this).val() == '<?= $post->chan ?>') {
										$(this).prop('selected', true);
									}
								});
							});
						</script>
						<input type="datetime-local" class="text" value="<?= $post->date ?>" name="date">
						<div class="box-buttons">
							<a class="button button-small" href="logic.php?act=post-send&id=<?= $post->id ?>">Пост</a>
							<a class="button button-small" href="logic.php?act=post-remove&id=<?= $post->id ?>">Убрать</a>
							<button class="button button-small" type="submit">Сохранить</button>
						</div>
					</div>
				</form>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php include('includes/footer.php') ?>
