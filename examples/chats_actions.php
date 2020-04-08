<?php
// групповые беседы
require_once "../vendor/autoload.php";
require_once "config.php";

try {
    $icq = new Antson\IcqBot\Client(TOKEN);

// редактирование описания чата
    if (false) {
        $result = $icq->setTitle(DEBUG_CHAT_ID, "Общий чат точки " . Date("H:i"));
        if (!$result->isOk()) {
            echo $result->error_msg() . '<br>';
            echo '<p>Для этой команды бот должен быть админом. Найдите его в списке участников . Три точки справа. Назначить админом.</p>';
        }
        $result = $icq->setAbout(DEBUG_CHAT_ID, "Описание чата может назначаться ботом. " . Date("H:i"));
        $result = $icq->setRules(DEBUG_CHAT_ID, "Бот может заполнить правила беседы. " . Date("H:i"));
    }

// списки участников чата
    if (true) {
        $admins = $icq->getAdmins(DEBUG_CHAT_ID);
        echo '<pre>';
        print_r($admins);
        echo '</pre>';

        $members = $icq->getMembers(DEBUG_CHAT_ID);
        echo '<pre>';
        print_r($members);
        echo '</pre>';

        $blocked = $icq->getBlockedUsers(DEBUG_CHAT_ID);
        echo '<pre>';
        print_r($blocked);
        echo '</pre>';

        $pending = $icq->getPendingUsers(DEBUG_CHAT_ID);
        echo '<pre>';
        print_r($pending);
        echo '</pre>';
    }

} catch (Exception $e) {
    echo $e->getMessage();
}