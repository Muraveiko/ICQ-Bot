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
 * Class UrlButton
 * @package Antson\IcqBot\Keyboard
 */
class UrlButton extends Button
{
    /**
     * UrlButton constructor.
     * @param $text
     * @param $url
     */
    public function __construct($text,$url)
    {
        $this->object = (object)['text'=>$text,'url'=>$url];
    }

}