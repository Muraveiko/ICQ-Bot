<?php


namespace Antson\IcqBot\Keyboard;


class CallbackButton extends Button
{
    public function __construct($text,$callbackData)
    {
        $this->object = (object)['text'=>$text,'callbackData'=>$callbackData];
    }

}