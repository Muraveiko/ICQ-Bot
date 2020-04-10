<?php
require_once "../vendor/autoload.php";
require_once "config.php";

use Antson\IcqBot\Entities\IcqEvent;
use Antson\IcqBot\Entities\PayloadMessage;
use Antson\IcqBot\Entities\PayloadNewChatMembers;

try {
    $icq = new Antson\IcqBot\Client(TOKEN);
    $events = $icq->getEvents();
    foreach ($events as $event){
        echo $event->get_eventId().') ['.$event->get_type().'] ';

        $payload = $event->get_payload();

        echo 'Payload instance of '.get_class($payload);
        echo '<br>';

        switch ($event->get_type()){
            case IcqEvent::NEW_MESSAGE:
                /** @var PayloadMessage $payload */
                echo "<b>Сообщение</b> от ".$payload->get_from()->get_firstName().' '.$payload->get_from()->get_lastName().'(@'.$payload->get_from()->get_userId()."):<br>";
                echo "<i>".$payload->get_text()."</i><br>";
                break;

            case IcqEvent::NEW_CHAT_MEMBERS:
                /** @var PayloadNewChatMembers $payload */
                echo "Пользователь ".$payload->get_addedBy()->get_firstName()." <b>добавил</b> в ".$payload->get_chat()->get_title().":<br>";
                $members = $payload->get_newMembers();
                foreach ($members as $member){
                    echo $member->get_userId().' '.$member->get_firstName().' '.$member->get_lastName();
                    echo "<br>";
                }
                break;

            /**
             * other IcqEvent::CONST
             */
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

