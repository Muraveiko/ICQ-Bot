<?php
/**
 * This file is part of the IcqBot package.
 *
 * (c) Oleg Muraveyko aka Antson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Antson\IcqBot\Keyboard;

/**
 * Class InlineKeyboard
 * @package Antson\IcqBot\Keyboard
 */
class InlineKeyboard
{
    /**
     * @var array для формирования массива кнопок <br>
     * [[{text:"",url:""},{text:"",callbackData:""}]]
     */
    private $keyboard = [];

    /**
     * InlineKeyboard constructor.
     * <br>
     * Реализованы 4 варианта описания клавиатуры:<br>
     * 1) instance of Button - из одной кнопки<br>
     * 2) Button[] - каждая кнопка в новом ряду<br>
     * 3) [Button,Button, ... ,Button],[Button,Button, ... ,Button]<br>
     * 4) одной переменной типа array из случая 3.<br>
     * <br>
     * @see examples/keyboard.php
     */
    public function __construct()
    {
        $args = func_get_args();
        if(count($args)==1 && is_array($args[0])){
            $args=$args[0];
        }

        $cur_row = 0;
        $cur_button = 0;
        foreach ($args as $arg){
            if($arg instanceof Button){
                $this->keyboard[$cur_row][$cur_button] = $arg->get_object();
                $cur_button++;
            }else if(is_array($arg)){
                foreach ($arg as $ar){
                    if($ar instanceof Button) {
                        $this->keyboard[$cur_row][$cur_button] = $ar->get_object();
                        $cur_button++;
                    }
                }
                if($cur_button>0){
                    $cur_row++;
                    $cur_button = 0;
                }
            }
            if($cur_button>0){
                $cur_row++;
                $cur_button = 0;
            }
        }
    }

    /**
     * В таком виде апи ожидает параметр, описывающий клавиатуру
     * @return string
     */
    public function toApiParam(){
        return json_encode($this->keyboard);
    }
}