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
 * Если у сообщения есть отправитель
 * @package Antson\IcqBot\Entities
 */
abstract class PayloadWithFrom extends Payload
{
    /** @var mixed требуется для более глубокого разбора ответа*/
    protected $from;

    /**
     * Отправитель сообщения / Автор
     * @return Author
     */
    public function get_from(){
        return new Author((array)$this->from);
    }
}