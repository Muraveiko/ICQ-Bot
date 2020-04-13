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
 * К нам присоединились участники
 * @package Antson\IcqBot\Entities
 */
class PayloadNewChatMembers extends Payload
{
    /** @var mixed для дальнейшего разбора*/
    protected $addedBy;

    /** @var mixed для дальнейшего разбора*/
    protected $newMembers;

    /**
     * Кто пригласил / Сам подписался
     * @return Member
     */
    public function get_addedBy(){
        return new Member((array)$this->addedBy);
    }

    /**
     * Новые участники
     * @return Member[]
     */
    public function get_newMembers(){
        $ret = [];
        if(is_array($this->newMembers)){
            foreach ($this->newMembers as $member){
                $ret[] = new Member((array)$member);
            }
        }
        return $ret;
    }
}