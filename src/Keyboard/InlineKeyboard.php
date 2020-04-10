<?php


namespace Antson\IcqBot\Keyboard;


class InlineKeyboard
{
    /**
     * @var array
     */
    private $keyboard = [];
    public function __construct()
    {
        $args = func_get_args();
        if(count($args)==1 && is_array($args[0])){
            $args=$args[0];
        }
//        var_dump($args);
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

    public function toApiParam(){
        return json_encode($this->keyboard);
    }
}