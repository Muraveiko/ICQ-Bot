<?php


namespace Antson\IcqBot\Entities;


abstract class PayloadWithFrom extends Payload
{
    /** @var mixed */
    protected $from;

    /**
     * @return Author
     */
    public function get_from(){
        return new Author((array)$this->from);
    }
}