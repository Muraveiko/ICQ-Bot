<?php
require_once "../vendor/autoload.php";
require_once "config.php";

$icq = new Antson\IcqBot\Client(TOKEN);
$botInfo = $icq->self_get();
if($botInfo->isOk()){
    echo 'userId: '.$botInfo->get_userId().'<br>';
    echo 'firstName: '.$botInfo->get_firstName().'<br>';
    echo 'nick: '.$botInfo->get_nick().'<br>';
    echo 'about: '.$botInfo->get_about().'<br>';
    echo '<pre>';
    print_r($botInfo->get_photo());
    echo '</pre>';
}
