<?php

require('core.php');

$date = date('Y-m-d') . 'T' . date('H:i');
$posts = dbs("select * from autochan where date = '$date'");

foreach($posts as $post) {
    if(checkOwner($post->chan, $post->user_id, $auto)) {
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
        dbu("delete from autochan where id = $post->id");
        if($post->image) {
            unlink('storage/' . $post->image);
        }
    }
}
