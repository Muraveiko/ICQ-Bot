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
 * UIN и его роль в чате. Используется в ответах от апи как элемент массивов
 *
 * @package Antson\IcqBot\Entities
 * @method string get_userId()
 *
 * @see Client::getAdmins(),Client::getMembers() и т.д.
 */
class User extends Entity
{
    /** @var bool  так этого поля может не быть в ответе */
    public $creator = false;

    /** @var bool так этого поля может не быть в ответе  */
    public $admin = false;


    /**
     * Это создатель группы / канала ?
     * @return bool
     */
    public function isCreator(){
        return $this->creator;
    }

    /**
     * Это админимтратор ?
     * @return bool
     */
    public function isAdmin(){
        return $this->admin;
    }

}