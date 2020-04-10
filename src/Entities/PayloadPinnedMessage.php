<?php


namespace Antson\IcqBot\Entities;

/**
 * Закреплено сообщение в чате
 * @method string get_msgId()
 * @method string get_timestamp()
 * @method string get_text() Текст сообщения от клиента для дальнейшего разбора
 */
class PayloadPinnedMessage extends PayloadWithFrom
{

}