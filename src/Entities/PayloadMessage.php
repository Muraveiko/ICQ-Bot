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
 * Сообщение
 * @method string get_msgId()
 * @method string get_timestamp()
 * @method string get_text() Текст сообщения от клиента для дальнейшего разбора
 */
abstract  class PayloadMessage extends PayloadWithFrom
{
    /**
     * @var mixed|null Для дальнейшего разбора
     */
    protected $parts = null;

    const PART_STICKER = "sticker";
    const PART_MENTION = "mention";
    const PART_VOICE = "voice";
    const PART_FILE = "file";
    const PART_FORWARD = "forward";
    const PART_REPLY = "reply";

    /**
     * Атачи к сообщению
     * @return array
     */
    public function get_parts()
    {
        $parts = [];
        if(!is_null($this->parts)){
            foreach ((array)$this->parts as $part){
                $part = (array)$part;
                switch ($part['type']){
                    case self::PART_FILE:
                        $parts[] =  new File((array)$part['payload']);
                        break;
                    case self::PART_FORWARD:
                        $parts[] =  new PayloadForwardMessage((array)$part['payload']);
                        break;
                    case self::PART_MENTION:
                        $parts[] = new Member((array)$part['payload']);
                        break;
                    case self::PART_REPLY:
                        $parts[] =  new PayloadReplyMessage((array)$part['payload']);
                        break;
                    case self::PART_STICKER:
                        $parts[] =   new File((array)$part['payload'],File::TYPE_IMAGE);
                        break;
                    case self::PART_VOICE:
                        $parts[] = new File((array)$part['payload'],File::TYPE_VOICE,'');
                        break;
                }
            }
        }
        return $parts;
    }
}