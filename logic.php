<?php

require('core.php');
$act = request('act');

switch($act) {

	case 'success': {
		$user_data = json_decode(file_get_contents(DOMAIN . 'bots/auto.php?code=' . request('code')));
		session('user', $user_data);
		echo json_encode($user_data);
		break;
	}

	case 'logout': {
		session_unset();
		redirect('index.php');
		break;
	}

	case 'post-add': {
		$chan = request('chan');
		$image = '';
		$content = request('content');
		$date = request('date');
		if($_FILES['image']['size']) {
			$image = uniqid() . '.jpg';
			$fname = 'storage/' . $image;
			move_uploaded_file($_FILES['image']['tmp_name'], $fname);
			chmod($fname, 0777);
			img_do_resize($fname);
		}
		if($image) {
			$content = mb_substr($content, 0, 200);
		}
		dbi("insert into autochan (user_id, chan, image, content, date) values ('$user->user_id', '$chan', '$image', '$content', '$date')");
		back();
		break;
	}

	case 'post-save': {
		$chan = request('chan');
		$image = request('image');
		$content = request('content');
		$date = request('date');
		if($image) {
			$content = mb_substr($content, 0, 200);
		}
		dbi("update autochan set chan = '$chan', content = '$content', date = '$date'");
		back();
		break;
	}

	case 'post-remove': {
		$id = request('id');
		$post = dbs("select * from autochan where id = $id and user_id = '$user->user_id'")[0];
		dbu("delete from autochan where id = $id and user_id = '$user->user_id'");
		if($post->image) {
			unlink('storage/' . $post->image);
		}
		redirect('workflow.php');
		break;
	}

	case 'post-send': {
		$id = request('id');
		$post = dbs("select * from autochan where id = $id and user_id = '$user->user_id'")[0];

		if(checkOwner($post->chan, $user->user_id, $auto)) {
			if($post->image) {
				$auto->sendPhoto([
					'chat_id' => $post->chan,
					'photo' => 'storage/' . $post->image,
					'caption' => $post->content
				]);
			}
			else {
				$auto->sendMessage([
					'chat_id' => $post->chan,
					'text' => $post->content,
					'parse_mode' => 'Markdown'
				]);
			}
			redirect('?act=post-remove&id=' . $id);
		}
		else {
			back();
		}
		break;
	}

	case 'chan-add': {
		$name = request('name');
		if(checkOwner($name, $user->user_id, $auto)) {
			dbi("insert into autochan_chans (user_id, name) values ('$user->user_id', '$name')");
		}
		back();
		break;
	}

	case 'chan-remove': {
		$id = request('id');
		dbu("delete from autochan_chans where id = $id and user_id = '$user->user_id'");
		back();
		break;
	}

}
