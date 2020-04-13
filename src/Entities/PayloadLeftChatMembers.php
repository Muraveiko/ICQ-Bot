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
 * Беседу покинули участники
 * @package Antson\IcqBot\Entities
 */
class PayloadLeftChatMembers extends Payload
{
    /** @var mixed требуется для более глубокого разбора ответа*/
    protected $removedBy;

    /** @var mixed требуется для более глубокого разбора ответа*/
    protected $leftMembers;

    /**
     * Кто удалил или сам покинул
     * @return Member
     */
    public function get_removedBy(){
        return new Member((array)$this->removedBy);
    }

    /**
     * Кто больше не участвует в группе или не подписан на канал
     * @return Member[]
     */
    public function get_leftMembers(){
        $ret = [];
        if(is_array($this->leftMembers)){
            foreach ($this->leftMembers as $member){
                $ret[] = new Member((array)$member);
            }
        }
        return $ret;
    }

}