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

    private function _get_default_param(){
        return array('token'=>$this->token);
    }

    /**
     * @param string $method
     * @param array $param
     * @return false|string
     */
    private function _do_request($method,$param){
        return $this->curler->get($this->api_url.$method.'?'.http_build_query($param));
    }

    /**
     * Метод можно использовать для проверки валидности токена.
     * @return BotInfo
     */
    public function self_get(){
        $param = $this->_get_default_param();
        return new BotInfo($this->_do_request('self/get',$param));
    }

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
        $param = $this->_get_default_param();
        $param['chatId'] = $chatId;
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
     * @param string $chatId
     * @param string $msgId
     * @param string $text
     * @return Entity
     */
    public function editText($chatId,$msgId,$text){
        $param = $this->_get_default_param();
        $param['chatId'] = $chatId;
        $param['msgId'] = $msgId;
        $param['text'] = $text;
        return new Entity($this->_do_request('messages/editText',$param));
    }
}
