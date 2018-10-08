<?php
$is_auth = rand(0, 1);

if (!$is_auth) {
    return [];
}
return [
        'NAME' =>'Сергей',
        'AVATAR_SRC' => 'img/user.jpg'
        ];