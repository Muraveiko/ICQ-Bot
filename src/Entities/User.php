<?php


namespace Antson\IcqBot\Entities;

/**
 * Class User
 * @package Antson\IcqBot\Entities
 * @method string get_userId()
 */
class User extends Entity
{
    /** @var bool  */
    public $creator = false;

    /** @var bool  */
    public $admin = false;


    public function isCreator(){
        return $this->creator;
    }

    public function isAdmin(){
        return $this->admin;
    }

}