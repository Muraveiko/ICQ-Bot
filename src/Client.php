<?php
/**
 * This file is part of the IcqBot package.
 *
 * (c) Oleg Muraveyko aka Antson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Antson\IcqBot;

use Antson\IcqBot\Entities\Entity;
use Antson\IcqBot\Entities\IcqEvent;
use Antson\IcqBot\Entities\SendResult;
use Antson\IcqBot\Entities\SendFileResult;
use Antson\IcqBot\Entities\BotInfo;
use Antson\IcqBot\Entities\ChatInfo;
use Antson\IcqBot\Entities\ChatInfoChannel;
use Antson\IcqBot\Entities\ChatInfoGroup;
use Antson\IcqBot\Entities\ChatInfoPrivate;
use Antson\IcqBot\Entities\FileInfo;
use Antson\IcqBot\Entities\User;

use Antson\IcqBot\Keyboard\InlineKeyboard;

use CurlFile;
use Muraveiko\PhpCurler\Curler;

/**
 * Выполнение запросов к ICQ API
 * @package Antson\IcqBot
 */
class Client
{
    /**
     * @var string Урл апи по умолчанию. Необязательный параметр в конструкторе
     */
    private $api_url = 'https://api.icq.net/bot/v1/';

    /**
     * @var string Ключ к апи
     */
    private $token = '';

    /**
     * @var string Ид бота. Находится автоматически из ключа
     */
    private $uin = '';

    /**
     * @var int see __constructor
     */
    private $get_timeout;

    /**
     * @var int при отправке файлов на сервер
     */
    private $post_timeout = 10000 ;

    /**
     * Если для отправки большого файла нехватает времени
     * @param int $post_timeout в миллисекундах
     */
    public function setPostTimeout($post_timeout)
    {
        $this->post_timeout = $post_timeout;
    }

    /**
     * Client constructor.
     * @param string $token
     * @param string|null $api_url
     * @param int $timeout в милисекундах для гет запросов к апи
     * @throws Exception
     */
    public function __construct($token, $api_url = null,$timeout = 5000)
    {
        $this->token = $token;
        $this->get_timeout = $timeout;

        list(, $this->uin) = explode(':', $token);
        if (empty($this->uin)) {
            throw new Exception("wrong token");
        }
        if (!is_null($api_url)) {
            $this->api_url = $api_url;
        }
    }

    /**
     * готовим массив параметров запроса
     * @param string|null $chatId
     * @return array
     */
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
     * get запрос к api
     * @param string $method
     * @param array $param
     * @return string
     * @throws Exception
     */
    private function _do_request($method, $param)
    {
        /**
         * @var Curler
         */
        $curler = new Curler([
            'timeout' => $this->get_timeout,
            'validMimeTypes' => 'application/json'
        ]);
        $param = array_map(function($item) {
            if ($item === true)
                $item = 'true';
            else if ($item === false) {
                $item = 'false';
            }
            return $item;
        }, $param);
        $r = $curler->get($this->api_url . $method . '?' . http_build_query($param));
        if ($r === false) {
            $curler_error = $curler->getError();
            throw new ExceptionLan($curler_error['message']);
        }
        return $r;
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
    //  API: SELF
    // =======================================================================================================

    /**
     * Метод можно использовать для проверки валидности токена. Информация об самом боте
     * @return BotInfo
     * @throws Exception
     */
    public function self_get()
    {
        $param = $this->_get_default_param();
        return new BotInfo($this->_do_request('self/get', $param));
    }


    // =======================================================================================================
    // API: MESSAGES
    // =======================================================================================================


    /**
     * Отправить текстовое сообщение
     * @param string $chatId
     * @param string $text
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param InlineKeyboard|null $inlineKeyboardMarkup
     * @return SendResult
     * @throws Exception
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
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup->toApiParam();
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
     * @param InlineKeyboard|null $inlineKeyboardMarkup
     * @return SendFileResult
     * @throws Exception
     */
    public function sendPresentFile($chatId, $fileId, $caption = null, $replyMsgId = null, $forwardMsgId = null, $forwardChatId = null, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
        $param['fileId'] = $fileId;
        if (!is_null($caption)) {
            $param['caption'] = $caption;
        }
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
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup->toApiParam();
        }

        return new SendFileResult($this->_do_request('messages/sendFile', $param));

    }

    /**
     * Отправить файл
     * @param string $chatId
     * @param CurlFile $curlFile
     * @param string|null $caption
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param InlineKeyboard|null $inlineKeyboardMarkup
     * @return SendFileResult
     * @throws ExceptionLan
     */
    public function sendNewFile($chatId, $curlFile, $caption = null, $replyMsgId = null, $forwardMsgId = null, $forwardChatId = null, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
        if (!is_null($caption)) {
            $param['caption'] = $caption;
        }
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
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup->toApiParam();
        }
        $param['file'] = $curlFile;

        /**
         * @var Curler
         */
        $curler = new Curler([
            'timeout' => $this->post_timeout,
            'validMimeTypes' => 'application/json'
        ]);

        $response = $curler->post($this->api_url . 'messages/sendFile', $param, false);
        if ($response === false) {
            $curler_error = $curler->getError();
            throw new ExceptionLan($curler_error['message']);
        }
        return new SendFileResult($response);
    }

    /**
     * Отправить ранее загруженный голосовое сообщение
     * @param string $chatId
     * @param string $fileId
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param InlineKeyboard|null $inlineKeyboardMarkup
     * @return SendFileResult
     * @throws Exception
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
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup->toApiParam();
        }

        return new SendFileResult($this->_do_request('messages/sendVoice', $param));
    }

    /**
     * Загрузить и отправить новое голосовое сообщение
     * Если вы хотите, чтобы клиент отображал этот файл как воспроизводимое голосовое сообщение, он должен быть в формате aac, ogg или m4a.
     * @param string $chatId
     * @param CurlFile $curlFile
     * @param string|null $replyMsgId
     * @param string|null $forwardMsgId
     * @param string|null $forwardChatId
     * @param InlineKeyboard|null $inlineKeyboardMarkup
     * @return SendFileResult
     * @throws ExceptionLan
     */
    public function sendNewVoice($chatId, $curlFile, $replyMsgId = null, $forwardMsgId = null, $forwardChatId = null, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
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
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup->toApiParam();
        }
        $param['file'] = $curlFile;

        /**
         * @var Curler
         */
        $curler = new Curler([
            'timeout' => $this->post_timeout,
            'validMimeTypes' => 'application/json'
        ]);

        $response = $curler->post($this->api_url . 'messages/sendVoice', $param, false);
        if ($response === false) {
            $curler_error = $curler->getError();
            throw new ExceptionLan($curler_error['message']);
        }

        return new SendFileResult($response);
    }

    /**
     * Редактировать сообщение
     * @param string $chatId
     * @param string $msgId
     * @param string $text
     * @param InlineKeyboard|null $inlineKeyboardMarkup
     * @return Entity
     * @throws Exception
     */
    public function editText($chatId, $msgId, $text, $inlineKeyboardMarkup = null)
    {
        $param = $this->_get_default_param($chatId);
        $param['msgId'] = $msgId;
        $param['text'] = $text;
        if (!is_null($inlineKeyboardMarkup)) {
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup->toApiParam();
        }
        return new Entity($this->_do_request('messages/editText', $param));
    }

    /**
     * Удалить сообщения
     * @param string $chatId
     * @param string $msgId
     * @return Entity
     * @throws Exception
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
     * @throws Exception
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
    // API: CHATS
    // =======================================================================================================

    const ACTION_LOOKING = "looking";
    const ACTION_TYPING = "typing";
    /**
     * Отправить текущие действия в чат
     * @param string $chatId
     * @param string[] $actions - ACTION_LOOKING ,ACTION_TYPING или пустой значение, если все действия завершены
     * @return Entity
     * @throws Exception
     * @see Client::ACTION_TYPING, Client::ACTION_LOOKING
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
     * @return ChatInfo|ChatInfoPrivate|ChatInfoGroup|ChatInfoChannel
     * @throws Exception
     */
    public function chatGetInfo($chatId)
    {
        $param = $this->_get_default_param($chatId);
        return ChatInfo::Fabric($this->_do_request('chats/getInfo', $param));
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
     * @throws Exception
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
     * @throws Exception
     */
    public function unblockUser($chatId, $userId)
    {
        $param = $this->_get_default_param($chatId);
        $param['userId'] = $userId;
        return new Entity($this->_do_request('chats/unblockUser', $param));
    }

    /**
     * Принять решение о пользователе, ожидающем вступления в чат
     * @param string $chatId
     * @param string $userId
     * @param bool $approve
     * @return Entity
     * @throws Exception
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
     * @param string $chatId
     * @param bool $approve
     * @return Entity
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
     */
    public function unpinMessage($chatId, $msgIds)
    {
        $param = $this->_get_default_param($chatId);
        $param['msgIds'] = $msgIds;
        return new Entity($this->_do_request('chats/unpinMessage', $param));
    }


    // =======================================================================================================
    // API:  FILES
    // =======================================================================================================

    /**
     * Получить информацию о файле
     * @param string $fileId
     * @return FileInfo
     * @throws Exception
     */
    public function fileGetInfo($fileId)
    {
        $param = $this->_get_default_param();
        $param['fileId'] = $fileId;
        return new FileInfo($this->_do_request('files/getInfo', $param));
    }

    // =======================================================================================================
    // API:  EVENTS
    // =======================================================================================================

    /**
     * Сообщения к боту
     * @param int $lastEventId Если Вы до этого передавали больше нуля, то апи это помнит и возврашать будет только новые.
     *                         т.е. Этот параметр можно использовать как подтверждение последнего обработанного Вами уведомления.
     *                         Вы не сможете переполучить заново подтвержденные сообщения, указав номер меньше чем хоть раз посланный.
     * @param int $pollTime in seconds
     * @return IcqEvent[]
     * @throws Exception
     */
    public function getEvents($lastEventId = 0, $pollTime = 25)
    {
        $events = [];

        $timeout = $pollTime + 1; // добавляем секунду, чтобы мы не завершали соединение раньше сервера
        $param = $this->_get_default_param();
        $param['lastEventId'] = $lastEventId;
        $param['pollTime'] = $pollTime;
        $listener = new Curler([
            'maxFilesize' => 16777216,  // 16M
            'validMimeTypes' => 'application/json',
            'timeout' => $timeout * 1000,
        ]);
        $response = $listener->get($this->api_url . 'events/get?' . http_build_query($param));
        if ($response === false) {
            $curler_error = $listener->getError();
            throw new ExceptionLan($curler_error['message']);
        }
        $data = json_decode($response);
        if(!empty($data->ok)) {
            if (isset($data->events)) {
                foreach ($data->events as $ev) {
                    $events[] = new IcqEvent((array)$ev);
                }
            }
        }
        return $events;
    }
}
