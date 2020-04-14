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
     * @param string $text
     * @param string $callbackData
     * @param string|null $style опциональное поле, отвечает за стиль текста на кнопке. see Button STYLE_xxx
     */
    public function __construct($text,$callbackData,$style=null)
    {
        $this->object = (object)['text'=>$text,'callbackData'=>$callbackData];
        if(!is_null($style)){
            $this->object->style = $style;
        }
    }

}