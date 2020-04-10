<?php


namespace Antson\IcqBot\Entities;


class PayloadNewChatMembers extends Payload
{
    /** @var mixed */
    protected $addedBy;

    /** @var mixed */
    protected $newMembers;

    /**
     * @return Member
     */
    public function get_addedBy(){
        return new Member((array)$this->addedBy);
    }

    /**
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