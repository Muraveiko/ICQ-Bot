<?php
/**
 * This file is part of the IcqBot package.
 *
 * (c) Oleg Muraveyko aka Antson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Antson\IcqBot\Entities;

/**
 * Информационная часть уведомления о событии
 * @package Antson\IcqBot\Entities
 */
abstract class Payload extends Entity
{
    /** @var mixed требуется для более глубокого разбора ответа*/
    protected $chat;
    /**
     * Информация в каком чате событие есть всегда.
     * В приватном беседе напрямую с пользователем совпадает со from
     * @return FromChat
     */
    public function get_chat(){
        return new FromChat((array)$this->chat);
    }
}