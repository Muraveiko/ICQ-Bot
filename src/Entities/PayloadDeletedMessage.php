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
 * Сообщение было удалено
 * @method string get_msgId() ид удаленного сообщения
 * @method string get_timestamp()
 */
class PayloadDeletedMessage extends Payload
{

}