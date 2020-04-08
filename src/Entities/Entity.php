<?php

namespace Antson\IcqBot\Entities;


class Entity
{
    /**
     * @param array|string  $data
     */
    public function __construct($data)
    {
        if(!is_array($data)){
            $data = json_decode($data,true);
        }
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    /**
     *
     * @param mixed $property
     * @param mixed $default
     *
     * @return mixed
     */
    public function getProperty($property, $default = null)
    {
        if (isset($this->$property)) {
            return $this->$property;
        }

        return $default;
    }

    /**
     *
     * @param $method
     * @param $args
     *
     * @return mixed|null
     */
    public function __call($method, $args)
    {
        //Convert method to snake_case (which is the name of the property)
        $property_name = substr($method, 4);
        $action = substr($method, 0, 4);
        if ($action === 'get_') {
            return  $this->getProperty($property_name);
        } elseif ($action === 'set_') {
                $this->$property_name = $args[0];
                return $this;
        }
        return null;
    }

    /**
     * @return boolean
     */
    public function isOk(){
        if(!isset($this->ok)){
            return false;
        }
        return $this->ok;
    }
}
