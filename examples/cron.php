<?php
set_time_limit(0);
require_once "../vendor/autoload.php";
require_once "config.php";

use Antson\IcqBot\Entities\IcqEvent;
use Antson\IcqBot\Entities\PayloadNewMessage;
use Antson\IcqBot\Keyboard\InlineKeyboard;
use Antson\IcqBot\Keyboard\UrlButton;
use Antson\IcqBot\Entities\ChatInfo;
use Antson\IcqBot\ExceptionLan;

if (php_sapi_name() !== "cli") {
    die('for console run only');
}

echo "start ";
$lastId = 0;
$lastPolledId = 0;
$start_time = microtime(1);
$max_time = 57;  // бот будет слушать 57 секунд из 60 . Запуск в кроне каждую минуту
$work = 0;
while (true) {
    $work = microtime(1) - $start_time;
    if ($work > $max_time) {
        echo "# "; // time end
        break;
    }
    $work = intval($work);
    $poll_time = min(30, intval($max_time - $work));
    try {
        echo "[".$work."/".$poll_time."] "; // прошло / ждать события
        $icq = new Antson\IcqBot\Client(TOKEN);
        $lastPolledId = $lastId;
        $events = $icq->getEvents($lastPolledId, $poll_time);
        foreach ($events as $event) {
            echo "* "; // received
            $payload = $event->get_payload();
            switch ($event->get_type()) {
                case IcqEvent::NEW_MESSAGE:
                    // отвечать бот будет только на личку
                    // и не важно разрешили или нет ему присоединяться в группы через Метабота
                    if ($payload->get_chat()->get_type() == ChatInfo::TYPE_PRIVATE) {
                        /** @var PayloadNewMessage $payload */
                        $from = $payload->get_from();
                        $icq->sendText($from->get_userId(),
                            "Уважаемый, " . $from->get_firstName() .
                            ' ' . $from->get_lastName() .
                            " ваш UIN " . $from->get_userId(),
                            null,
                            null,
                            null,
                            new InlineKeyboard(new UrlButton("PHP Library", "https://packagist.org/packages/antson/icq-bot"))
                        );
                        echo "+ "; // send
                    }

            }
            $lastId = $event->get_eventId();
        }
    } catch (Exception $e) {
        echo $e->getMessage() . " ";
        if($e instanceof ExceptionLan){
            die("Lan problem !");
        }
    }
}

if ($lastPolledId < $lastId) {
    // скажем апи до куда уведомления мы успели принять
    echo '[confirm ' . $lastId;
    try {
        $icq->getEvents($lastId, 0);
    } catch (Exception $e) {
        echo " ".$e->getMessage();
    }
    echo '] ';
}
echo "work time:" . $work . " end";
