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
 * Пользователь нажал на кнопку, прикрепленную к сообщению
 * @package Antson\IcqBot\Entities
 *
 * @method string get_queryId()
 * @method string get_callbackData()
 */
class PayloadCallbackQuery extends PayloadWithFrom
{
    /** @var mixed требуется для более глубокого разбора ответа*/
    protected $message;

    /**
     * Исходное событие от которого пришел callback
     * @return IcqEvent
     */
    public function get_message()
    {
        return new IcqEvent((array)$this->message);
    }
}