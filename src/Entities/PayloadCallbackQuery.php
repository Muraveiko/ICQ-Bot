<?php


namespace Antson\IcqBot\Entities;

/**
 * @method string get_queryId()
 * @method string get_callbackData()
 */
class PayloadCallbackQuery extends PayloadWithFrom
{
    /** @var mixed */
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