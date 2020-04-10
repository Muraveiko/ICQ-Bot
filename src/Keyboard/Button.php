<?php


namespace Antson\IcqBot\Keyboard;
use stdClass;

abstract class Button
{
    /** @var stdClass  */
    protected $object = null;

    /**
     * @return stdClass
     */
    public function get_object(){
       return $this->object;
    }
}