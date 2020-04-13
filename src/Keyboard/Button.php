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
use stdClass;

/**
 * Class Button
 * @package Antson\IcqBot\Keyboard
 */
abstract class Button
{
    /** @var stdClass кнопка описывается объектом */
    protected $object = null;

    /**
     * Для того что-бы собрать правильный json к вызову АПИ
     * @return stdClass
     */
    public function get_object(){
       return $this->object;
    }
}