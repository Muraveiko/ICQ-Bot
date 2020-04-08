<?php

namespace Antson\IcqBot;

use Antson\IcqBot\Entities\Entity;
use Antson\IcqBot\Entities\SendResult;
use Antson\IcqBot\Entities\BotInfo;
use Antson\IcqBot\Entities\ChatInfo;
use Antson\IcqBot\Entities\FileInfo;
use Antson\IcqBot\Entities\User;

use Muraveiko\PhpCurler\Curler;

class Client
{
    /**
     * @var string
     */
    private $api_url = 'https://api.icq.net/bot/v1/';

    /**
     * @var string
     */
    private $token = '';

    /**
     * @var string
     */
    private $uin = '';

    /**
     * @var Curler
     */
    private $curler;

    /**
     * Client constructor.
     * @param string $token
     * @param string|null $api_url
     * @throws Exception
     */
    public function __construct($token, $api_url = null)
    {
        $this->token = $token;
        list(, $this->uin) = explode(':', $token);
        if (empty($this->uin)) {
            throw new Exception("wrong token");
        }
        if (!is_null($api_url)) {
            $this->api_url = $api_url;
        }
        $this->curler = new Curler([
            'validMimeTypes' => 'application/json'
        ]);
    }

    private function _get_default_param($chatId = null)
    {
        $param = [
            'token' => $this->token
        ];
        if (!is_null($chatId)) {
            $param['chatId'] = $chatId;
        }
        return $param;
    }

    /**
     * @param string $method
     * @param array $param
     * @return false|string
     */
    private function _do_request($method, $param)
    {
        return $this->curler->get($this->api_url . $method . '?' . http_build_query($param));
    }

    /**
     * Идентификатор бота
     * @return string
     */
    public function myUIN()
    {
        return $this->uin;
    }

    // =======================================================================================================
    //      SELF
    // =======================================================================================================

    /**
     * Метод можно использовать для проверки валидности токена.
     * @return BotInfo
     */
    public function self_get()
    {
        $param = $this->_get_default_param();
        return new BotInfo($this->_do_request('self/get', $param));
    }


    // =======================================================================================================
    //     MESSAGES
    // =======================================================================================================


    /**
     * Отправить текстовое сообщение
     * @param string $chatId
     * @param string $text
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param array|null $inlineKeyboardMarkup
     * @return SendResult
     */
    public function sendText($chatId, $text, $replyMsgId = null, $forwardMsgId = null, $forwardChatId = null, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
        $param['text'] = $text;
        if (!is_null($replyMsgId)) {
            $param['replyMsgId'] = $replyMsgId;
        }
        if (!is_null($forwardMsgId)) {
            $param['forwardMsgId'] = $forwardMsgId;
        }
        if (!is_null($forwardChatId)) {
            $param['forwardChatId'] = $forwardChatId;
        }
        if (!is_null($inlineKeyboardMarkup)) {
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup;
        }

        return new SendResult($this->_do_request('messages/sendText', $param));
    }

    /**
     * Отправить ранее загруженный файл
     * @param string $chatId
     * @param string $fileId
     * @param string|null $caption
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param string|null $inlineKeyboardMarkup
     * @return SendResult
     */
    public function sendPresentFile($chatId, $fileId, $caption = null, $replyMsgId = null, $forwardMsgId = null, $forwardChatId = null, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
        $param['fileId'] = $fileId;
        $param['caption'] = $caption;
        if (!is_null($replyMsgId)) {
            $param['replyMsgId'] = $replyMsgId;
        }
        if (!is_null($forwardMsgId)) {
            $param['forwardMsgId'] = $forwardMsgId;
        }
        if (!is_null($forwardChatId)) {
            $param['forwardChatId'] = $forwardChatId;
        }
        if (!is_null($inlineKeyboardMarkup)) {
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup;
        }

        return new SendResult($this->_do_request('messages/sendFile', $param));

    }

    public function sendNewFile($chatId, $fileId)
    {
        /** @todo */
    }

    /**
     * Отправить ранее загруженный голосовое сообщение
     * @param string $chatId
     * @param string $fileId
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param string|null $inlineKeyboardMarkup
     * @return SendResult
     */
    public function sendPresentVoice($chatId, $fileId, $replyMsgId = null, $forwardMsgId = null, $forwardChatId = null, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
        $param['fileId'] = $fileId;
        if (!is_null($replyMsgId)) {
            $param['replyMsgId'] = $replyMsgId;
        }
        if (!is_null($forwardMsgId)) {
            $param['forwardMsgId'] = $forwardMsgId;
        }
        if (!is_null($forwardChatId)) {
            $param['forwardChatId'] = $forwardChatId;
        }
        if (!is_null($inlineKeyboardMarkup)) {
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup;
        }

        return new SendResult($this->_do_request('messages/sendVoice', $param));
    }

    public function sendNewVoice($chatId, $fileId)
    {
        /** @todo */
    }

    /**
     * Редактировать сообщение
     * @param string $chatId
     * @param string $msgId
     * @param string $text
     * @return Entity
     */
    public function editText($chatId, $msgId, $text)
    {
        $param = $this->_get_default_param($chatId);
        $param['msgId'] = $msgId;
        $param['text'] = $text;
        return new Entity($this->_do_request('messages/editText', $param));
    }

    /**
     * Удалить сообщения
     * @param string $chatId
     * @param string $msgId
     * @return Entity
     */
    public function deleteMessage($chatId, $msgId)
    {
        $param = $this->_get_default_param($chatId);
        $param['msgId'] = $msgId;
        return new Entity($this->_do_request('messages/deleteMessages', $param));
    }

    /**
     * Ответ на запрос обработки нажатия кнопки
     * @param string $queryId
     * @param string $text
     * @param bool $showAlert
     * @param string|null $url
     * @return Entity
     */
    public function answerCallbackQuery($queryId, $text, $showAlert = false, $url = null)
    {
        $param = $this->_get_default_param();
        $param['queryId'] = $queryId;
        $param['text'] = $text;
        $param['showAlert'] = $showAlert;
        if (!is_null($url)) {
            $param['url'] = $url;
        }
        return new Entity($this->_do_request('messages/answerCallbackQuery', $param));

    }
    // =======================================================================================================
    //     CHATS
    // =======================================================================================================

    /**
     * Отправить текущие действия в чат
     * @param string $chatId
     * @param [string] $actions - 'looking','typing' или пустой значение, если все действия завершены
     * @return Entity
     */
    public function sendActions($chatId, $actions)
    {
        $param = $this->_get_default_param($chatId);
        $param['actions'] = $actions;
        return new Entity($this->_do_request('chats/sendActions', $param));
    }

    /**
     * Получить информацию о чате
     * @param $chatId
     * @return ChatInfo
     */
    public function chatGetInfo($chatId)
    {
        $param = $this->_get_default_param($chatId);
        return new ChatInfo($this->_do_request('chats/getInfo', $param));
    }

    /**
     * Получить список администраторов чата
     * @param string $chatId
     * @return User[]
     * @throws Exception
     */
    public function getAdmins($chatId)
    {
        $param = $this->_get_default_param($chatId);
        $response = $this->_do_request('chats/getAdmins', $param);
        $data = json_decode($response, true);
        $r = [];
        if (!isset($data['admins'])) {
            $reason = 'Wrong response';
            if (isset($data->description)) {
                $reason = $data['description'];
            }
            throw new Exception($reason);
        }
        foreach ($data['admins'] as $u) {
            $u['admin'] = true;
            $r[] = new User($u);
        }
        return $r;
    }

    /**
     * Получить список участников чата
     * @param string $chatId
     * @param string|null $cursor
     * @return User[]
     * @throws Exception
     */
    public function getMembers($chatId, $cursor = null)
    {
        $param = $this->_get_default_param($chatId);
        if (!is_null($cursor)) {
            $param['cursor'] = $cursor;
        }
        $response = $this->_do_request('chats/getMembers', $param);
        $data = json_decode($response, true);
        $r = [];
        if (!isset($data['members'])) {
            $reason = 'Wrong response';
            if (isset($data->description)) {
                $reason = $data['description'];
            }
            throw new Exception($reason);
        }
        foreach ($data['members'] as $u) {
            if (!empty($u['creator'])) {
                $u['admin'] = true;  // мне так удобнее, так как создатель чата это тоже админ
            }
            $r[] = new User($u);
        }
        return $r;
    }

    /**
     * Получить список пользователей, которые заблокированы в чате
     * @param string $chatId
     * @return User[]
     * @throws Exception
     */
    public function getBlockedUsers($chatId)
    {
        $param = $this->_get_default_param($chatId);
        $response = $this->_do_request('chats/getBlockedUsers', $param);
        $data = json_decode($response, true);
        $r = [];
        if (!isset($data['users'])) {
            $reason = 'Wrong response';
            if (isset($data->description)) {
                $reason = $data['description'];
            }
            throw new Exception($reason);
        }
        foreach ($data['users'] as $u) {
            $r[] = new User($u);
        }
        return $r;
    }

    /**
     * Получить список пользователей, которые ожидают вступления в чат
     * @param string $chatId
     * @return User[]
     * @throws Exception
     */
    public function getPendingUsers($chatId)
    {
        $param = $this->_get_default_param($chatId);
        $response = $this->_do_request('chats/getPendingUsers', $param);
        $data = json_decode($response, true);
        $r = [];
        if (!isset($data['users'])) {
            $reason = 'Wrong response';
            if (isset($data->description)) {
                $reason = $data['description'];
            }
            throw new Exception($reason);
        }
        foreach ($data['users'] as $u) {
            $r[] = new User($u);
        }
        return $r;
    }

    /**
     * Заблокировать пользователя в чате
     * @param string $chatId Уникальный ник или id группы или канала.
     * @param string $userId Уникальный ник или id пользователя.
     * @param bool $delLastMessages
     * @return Entity
     */
    public function blockUser($chatId, $userId, $delLastMessages = true)
    {
        $param = $this->_get_default_param($chatId);
        $param['userId'] = $userId;
        $param['delLastMessages'] = $delLastMessages;
        return new Entity($this->_do_request('chats/blockUser', $param));
    }

    /**
     * Разблокировать пользователя в чате
     * @param string $chatId Уникальный ник или id группы или канала.
     * @param string $userId Уникальный ник или id пользователя.
     * @return Entity
     */
    public function unblockUser($chatId, $userId)
    {
        $param = $this->_get_default_param($chatId);
        $param['userId'] = $userId;
        return new Entity($this->_do_request('chats/unblockUser', $param));
    }

    /**
     * Принять решение о пользователе, ожидающем вступления в чат
     * @param $chatId
     * @param $userId
     * @param bool $approve
     * @return Entity
     */
    public function resolvePendingUser($chatId, $userId, $approve = true)
    {
        $param = $this->_get_default_param($chatId);
        $param['userId'] = $userId;
        $param['approve'] = $approve;
        return new Entity($this->_do_request('chats/resolvePending', $param));
    }

    /**
     * Принять решение о всех пользователях, ожидающих вступления в чат
     * @param $chatId
     * @param bool $approve
     * @return Entity
     */
    public function resolvePendingEveryone($chatId, $approve = true)
    {
        $param = $this->_get_default_param($chatId);
        $param['everyone'] = true;
        $param['approve'] = $approve;
        return new Entity($this->_do_request('chats/resolvePending', $param));
    }

    /**
     * Изменить название чата
     * Для вызова этого метода бот должен быть администратором в чате.
     * @param string $chatId
     * @param string $title
     * @return Entity
     */
    public function setTitle($chatId, $title)
    {
        $param = $this->_get_default_param($chatId);
        $param['title'] = $title;
        return new Entity($this->_do_request('chats/setTitle', $param));
    }

    /**
     * Изменить описание чата
     * Для вызова этого метода бот должен быть администратором в чате.
     * @param string $chatId
     * @param string $about
     * @return Entity
     */
    public function setAbout($chatId, $about)
    {
        $param = $this->_get_default_param($chatId);
        $param['about'] = $about;
        return new Entity($this->_do_request('chats/setAbout', $param));
    }

    /**
     * Изменить правила чата
     * Для вызова этого метода бот должен быть администратором в чате.
     * @param string $chatId
     * @param string $rules
     * @return Entity
     */
    public function setRules($chatId, $rules)
    {
        $param = $this->_get_default_param($chatId);
        $param['rules'] = $rules;
        return new Entity($this->_do_request('chats/setRules', $param));
    }

    /**
     * Закрепить сообщение в чате
     * @param string $chatId
     * @param array[string] $msgIds
     * @return Entity
     */
    public function pinMessage($chatId, $msgIds)
    {
        $param = $this->_get_default_param($chatId);
        $param['msgIds'] = $msgIds;
        return new Entity($this->_do_request('chats/pinMessage', $param));
    }

    /**
     * Открепить сообщение в чате
     * @param string $chatId
     * @param array[string] $msgIds
     * @return Entity
     */
    public function unpinMessage($chatId, $msgIds)
    {
        $param = $this->_get_default_param($chatId);
        $param['msgIds'] = $msgIds;
        return new Entity($this->_do_request('chats/unpinMessage', $param));
    }


    // =======================================================================================================
    //     FILES
    // =======================================================================================================

    /**
     * @param $fileId
     * @return FileInfo
     */
    public function fileGetInfo($fileId)
    {
        $param = $this->_get_default_param();
        $param['fileId'] = $fileId;
        return new FileInfo($this->_do_request('files/getInfo', $param));
    }

    // =======================================================================================================
    //     EVENTS
    // =======================================================================================================

    /* Сделаю возможно в будущем */
}
