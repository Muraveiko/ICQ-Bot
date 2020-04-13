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
 * This is the base class for all entities.
 *
 * Используется для разбор ответа от АПИ
 */
class Entity
{
    /**
     * Entity constructor.
     *
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
     * Get a property from the current Entity
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
     * Return the variable for the called getter or magically set properties dynamically.
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
     * Статус выполнения запроса АПИ
     *
     * @return boolean
     */
    public function isOk(){
        if(!isset($this->ok)){
            return false;
        }
        return $this->ok;
    }

    /**
     * Детальное описание результата запроса
     *
     * @return string|null
     */
    public function error_msg(){
        if(!isset($this->description)){
            return null;
        }
        return $this->description;
    }
}
