<?php


namespace Antson\IcqBot\Entities;

use Antson\IcqBot\Exception;

/**
 * Чаты бывают трех типов
 * @method string get_type()
 */
abstract class ChatInfo extends Entity
{
    const TYPE_PRIVATE = "private";
    const TYPE_GROUP = "group";
    const TYPE_CHANNEL = "channel";

    /**
     * Decode response for ChatInfo query
     * @param $data
     * @return ChatInfoPrivate|ChatInfoGroup|ChatInfoChannel
     * @throws Exception
     */
    public static function Fabric($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        switch ($data['type']) {
            case self::TYPE_PRIVATE:
                return new ChatInfoPrivate($data);
            case self::TYPE_GROUP:
                return new ChatInfoGroup($data);
            case self::TYPE_CHANNEL:
                return new ChatInfoChannel($data);
        }
        throw new Exception("wrong info type");
    }
}