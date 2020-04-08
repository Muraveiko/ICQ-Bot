<?php
require_once "../vendor/autoload.php";
require_once "config.php";


$icq = new Antson\IcqBot\Client(TOKEN);
$result = $icq->sendText(DEBUG_UIN,'Hello,word! ' . Date("Y-m-d H:i:s"));
if($result->isOk()) {
    echo 'msgId:'.$result->get_msgId();
    sleep(10);
    $icq->editText(DEBUG_UIN,$result->get_msgId(), 'Здравствуй,Мир! '. Date("Y-m-d H:i:s"));
}
