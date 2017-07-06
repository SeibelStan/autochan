<?php

include('connect.php');

if(isset($_GET['code'])) {
	$code = mb_strcut(preg_replace('/\s/', '', $_GET['code']), 0, 32);
	$result = $db->query("select * from auth where code = '$code'");
	if($result->num_rows) {
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$row['photo'] = 'https://ktrade.kz/libs/bots/photos/' . $row['photo'];
			echo json_encode($row);
		}
	}
	else {
		echo json_encode([
			'error' => 404,
			'title' => 'user not authorized'
		]);
	}
	die();
}

$data = json_decode(file_get_contents('php://input'));

$message = $data->message;
$chat_id = $message->from->id;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$username = $message->from->username;

if(isset($message->chat)) {
	$chat_id = $message->chat->id;
}

if(isset($message->text)) {

	$text = urldecode(trim($message->text));

	if(preg_match('/\/start /', $text)) {
		$code = preg_replace('/\/start /', '', $text);
		$code = mb_strcut(preg_replace('/\s/', '', $code), 0, 32);
	}

	if(isset($code) && $code) {
		$photos = $auto->getUserProfilePhotos([
			'user_id' => $chat_id,
			'offset' => 0,
			'limit' => 1
		])['photos'][0];

		$photoid = $photos[0]['file_id'];
		$photo = $auto->getFile([
			'file_id' => $photoid
		]);
		$photofile = file_get_contents('https://api.telegram.org/file/bot' . $auto_token . '/' . $photo['file_path']);
		$f = fopen('photos/' . $photoid, 'wb');
		fwrite($f, $photofile);

		$db->query("delete from auth where user_id = '$chat_id'");
		$db->query("insert into auth (code, user_id, first_name, last_name, username, photo) values ('$code', $chat_id, '$first_name', '$last_name', '$username', '$photoid')");

		$response = 'Вы авторизованы, вернитесь на сайт';
		$auto->sendMessage([
			'chat_id' => $chat_id,
			'text' => $response
		]);
		die();
	}

}
