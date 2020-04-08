<?php


namespace Antson\IcqBot\Entities;

use Antson\IcqBot\Exception;

/**
 * Class ChatInfo
 * @package Antson\IcqBot\Entities
 * @method string get_type()
 */
abstract  class ChatInfo extends Entity
{
    /**
     * Decode response for ChatInfo query
     * @param $data
     * @return ChatInfoPrivate|ChatInfoGroup|ChatInfoChannel
     * @throws Exception
     */
    public static function Fabric($data){
        if(!is_array($data)){
            /** @var ChatInfo $data */
            $data = json_decode($data,true);
        }
        switch ($data['type']){
            case 'private':
                return new ChatInfoPrivate($data);
            case 'group':
                return new ChatInfoGroup($data);
            case 'channel':
                return new ChatInfoChannel($data);
        }
        throw new Exception("wrong info type");
    }
}