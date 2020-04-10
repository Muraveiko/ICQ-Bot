<?php


namespace Antson\IcqBot\Entities;


class PayloadLeftChatMembers extends Payload
{
    /** @var mixed */
    protected $removedBy;

    /** @var mixed */
    protected $leftMembers;

    /**
     * @return Member
     */
    public function get_removedBy(){
        return new Member((array)$this->removedBy);
    }

    /**
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