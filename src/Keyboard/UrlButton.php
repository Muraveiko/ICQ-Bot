<?php


namespace Antson\IcqBot\Keyboard;


class UrlButton extends Button
{
    public function __construct($text,$url)
    {
        $this->object = (object)['text'=>$text,'url'=>$url];
    }

}