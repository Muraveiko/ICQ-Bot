<?php


namespace Antson\IcqBot\Entities;

use Antson\IcqBot\Exception;

/**
 * Уведомление о событии для бота
 * @method int get_eventId()
 * @method int get_type()
 */
class IcqEvent extends Entity
{
    const NEW_MESSAGE = "newMessage";
    const EDITED_MESSAGE = "editedMessage";
    const DELETED_MESSAGE = "deletedMessage";
    const PINNED_MESSAGE = "pinnedMessage";
    const UNPINNED_MESSAGE = "unpinnedMessage";
    const NEW_CHAT_MEMBERS = "newChatMembers";
    const LEFT_CHAT_MEMBERS = "leftChatMembers";
    const CHANGED_CHAT_INFO = "changedChatInfo";
    const CALLBACK_QUERY = "callbackQuery";

    /** @var mixed */
    protected $payload;

    /**
     * @return Payload
     * @throws Exception
     */
    public function get_payload()
    {
        switch ($this->get_type()) {
            case self::CALLBACK_QUERY:
                return new PayloadCallbackQuery((array)$this->payload);
            case self::CHANGED_CHAT_INFO:
                return new PayloadChangedChatInfo((array)$this->payload);
            case self::DELETED_MESSAGE:
                return new PayloadDeletedMessage((array)$this->payload);
            case self::EDITED_MESSAGE:
                return new PayloadEditedMessage((array)$this->payload);
            case self::LEFT_CHAT_MEMBERS:
                return new PayloadLeftChatMembers((array)$this->payload);
            case self::NEW_CHAT_MEMBERS:
                return new PayloadNewChatMembers((array)$this->payload);
            case self::NEW_MESSAGE:
                return new PayloadNewMessage((array)$this->payload);
            case self::PINNED_MESSAGE:
                return new PayloadPinnedMessage((array)$this->payload);
            case self::UNPINNED_MESSAGE:
                return new PayloadUnpinnedMessage((array)$this->payload);
        }
        throw new Exception("wrong payload type: " . $this->get_type());
    }
}