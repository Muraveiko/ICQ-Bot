<?php


namespace Antson\IcqBot\Entities;

/**
 * Информационная часть уведомления о событии
 * @package Antson\IcqBot\Entities
 */
abstract class Payload extends Entity
{
    /** @var mixed */
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