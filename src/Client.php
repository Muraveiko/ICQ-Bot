<?php
namespace Antson\IcqBot;

use Antson\IcqBot\Entities\Entity;
use Antson\IcqBot\Entities\BotInfo;
use Antson\IcqBot\Entities\SendResult;

use Muraveiko\PhpCurler\Curler;

class Client
{
    /**
     * @var string
     */
    private $api_url='https://api.icq.net/bot/v1/';

    /**
     * @var string
     */
    private $token = '';

    /**
     * @var Curler
     */
    private $curler;

    /**
     * Client constructor.
     * @param string $token
     * @param string|null $api_url
     */
    public function __construct($token,$api_url=null)
    {
        $this->token = $token;
        if(!is_null($api_url)){
            $this->api_url=$api_url;
        }
        $this->curler = new Curler(array(
            'validMimeTypes' => 'application/json'
        ));
    }

    private function _get_default_param($chatId=null){
        $param = array('token'=>$this->token);
        if(!is_null($chatId)) {
            $param['chatId'] = $chatId;
        }
        return $param;
    }

    /**
     * @param string $method
     * @param array $param
     * @return false|string
     */
    private function _do_request($method,$param){
        return $this->curler->get($this->api_url.$method.'?'.http_build_query($param));
    }


    // =======================================================================================================
    //      SELF
    // =======================================================================================================

    /**
     * Метод можно использовать для проверки валидности токена.
     * @return BotInfo
     */
    public function self_get(){
        $param = $this->_get_default_param();
        return new BotInfo($this->_do_request('self/get',$param));
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
    public function sendText($chatId,$text,$replyMsgId = null,$forwardMsgId=null,$forwardChatId = null,$inlineKeyboardMarkup=null){
        $param = $this->_get_default_param($chatId);
        $param['text'] = $text;
        if(!is_null($replyMsgId)){
            $param['replyMsgId'] = $replyMsgId;
        }
        if(!is_null($forwardMsgId)){
            $param['forwardMsgId'] = $forwardMsgId;
        }
        if(!is_null($forwardChatId)){
            $param['forwardChatId'] = $forwardChatId;
        }
        if(!is_null($inlineKeyboardMarkup)){
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup;
        }

        return new SendResult($this->_do_request('messages/sendText',$param));
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
    public function sendPresentFile($chatId,$fileId,$caption=null,$replyMsgId = null,$forwardMsgId=null,$forwardChatId = null,$inlineKeyboardMarkup=null){
        $param = $this->_get_default_param($chatId);
        $param['fileId'] = $fileId;
        $param['caption'] = $caption;
        if(!is_null($replyMsgId)){
            $param['replyMsgId'] = $replyMsgId;
        }
        if(!is_null($forwardMsgId)){
            $param['forwardMsgId'] = $forwardMsgId;
        }
        if(!is_null($forwardChatId)){
            $param['forwardChatId'] = $forwardChatId;
        }
        if(!is_null($inlineKeyboardMarkup)){
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup;
        }

        return new SendResult($this->_do_request('messages/sendFile',$param));

    }

    public function sendNewFile($chatId,$fileId){

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
    public function sendPresentVoice($chatId,$fileId,$replyMsgId = null,$forwardMsgId=null,$forwardChatId = null,$inlineKeyboardMarkup=null){
        $param = $this->_get_default_param($chatId);
        $param['fileId'] = $fileId;
        if(!is_null($replyMsgId)){
            $param['replyMsgId'] = $replyMsgId;
        }
        if(!is_null($forwardMsgId)){
            $param['forwardMsgId'] = $forwardMsgId;
        }
        if(!is_null($forwardChatId)){
            $param['forwardChatId'] = $forwardChatId;
        }
        if(!is_null($inlineKeyboardMarkup)){
            $param['inlineKeyboardMarkup'] = $inlineKeyboardMarkup;
        }

        return new SendResult($this->_do_request('messages/sendVoice',$param));
    }

    public function sendNewVoice($chatId,$fileId){

    }

    /**
     * Редактировать сообщение
     * @param string $chatId
     * @param string $msgId
     * @param string $text
     * @return Entity
     */
    public function editText($chatId,$msgId,$text){
        $param = $this->_get_default_param($chatId);
        $param['msgId'] = $msgId;
        $param['text'] = $text;
        return new Entity($this->_do_request('messages/editText',$param));
    }

    /**
     * Удалить сообщения
     * @param string $chatId
     * @param array[string] $msgIds
     * @return Entity
     */
    public function deleteMessages($chatId,$msgIds){
        $param = $this->_get_default_param($chatId);
        $param['msgIds'] = $msgIds;
        return new Entity($this->_do_request('messages/editText',$param));
    }

    /**
     * Ответ на запрос обработки нажатия кнопки
     * @param string $queryId
     * @param string $text
     * @param bool $showAlert
     * @param string|null $url
     * @return Entity
     */
    public function answerCallbackQuery($queryId,$text,$showAlert=false,$url=null){
        $param = $this->_get_default_param();
        $param['queryId'] = $queryId;
        $param['text'] = $text;
        $param['showAlert'] = $showAlert;
        if(!is_null($url)){
            $param['url'] = $url;
        }
        return new Entity($this->_do_request('messages/answerCallbackQuery',$param));

    }
    // =======================================================================================================
    //     CHATS
    // =======================================================================================================

    public function sendActions(){

    }

    public function chatGetInfo(){

    }

    public function getAdmins(){

    }

    public function getMembers(){

    }

    public function getBlockedUsers(){

    }

    public function getPendingUsers(){

    }

    public function blockUser(){

    }

    public function unblockUser(){

    }

    public function resolvePending(){

    }

    public function setTitle(){

    }

    public function setAbout(){

    }

    public function setRules(){

    }

    public function pinMessage(){

    }

    public function unpinMessage(){

    }

    // =======================================================================================================
    //     FILES
    // =======================================================================================================

    public function fileGetInfo(){

    }

    // =======================================================================================================
    //     EVENTS
    // =======================================================================================================



}
