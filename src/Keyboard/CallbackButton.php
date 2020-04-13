<?php
/**
 * This file is part of the IcqBot package.
 *
 * (c) Oleg Muraveyko aka Antson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Antson\IcqBot\Keyboard;

/**
 * Class CallbackButton
 * @package Antson\IcqBot\Keyboard
 */
class CallbackButton extends Button
{
    /**
     * CallbackButton constructor.
     * @param $text
     * @param $callbackData
     */
    public function __construct($text,$callbackData)
    {
        $this->object = (object)['text'=>$text,'callbackData'=>$callbackData];
    }

}