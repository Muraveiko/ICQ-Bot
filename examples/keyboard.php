<?php
require_once "../vendor/autoload.php";
require_once "config.php";

use \Antson\IcqBot\Keyboard\InlineKeyboard;
use \Antson\IcqBot\Keyboard\UrlButton;
use Antson\IcqBot\Keyboard\CallbackButton;

// просто кнопку
$keyboard0 = new InlineKeyboard(new UrlButton("PHP Library", "https://packagist.org/packages/antson/icq-bot"));
show_debug($keyboard0, "просто кнопку");

// список кнопок
$keyboard1 = new InlineKeyboard(
    new UrlButton("PHP Library", "https://packagist.org/packages/antson/icq-bot"),
    new UrlButton("API DOC", "https://icq.com/botapi"),
    new UrlButton("Tutorial", "https://icq.com/botapi/botTutorial.html")
);
show_debug($keyboard1, "список кнопок");


// каждая строка как массив
$keyboard2 = new InlineKeyboard(
    [
        new UrlButton("PHP Library", "https://packagist.org/packages/antson/icq-bot")
    ],
    [
        new CallbackButton("★☆☆☆☆", "callback1"),
        new CallbackButton("★★☆☆☆", "callback2"),
        new CallbackButton("★★★☆☆", "callback3"),
        new CallbackButton("★★★★☆", "callback4"),
        new CallbackButton("★★★★★", "callback5"),
    ],
    [
        new UrlButton("API DOC", "https://icq.com/botapi"),
        new UrlButton("Tutorial", "https://icq.com/botapi/botTutorial.html")
    ]
);
show_debug($keyboard2, "каждая строка как массив");

// одним параметром массив массивов
$keyboard3 = new InlineKeyboard([
    [
        new UrlButton("PHP Library", "https://packagist.org/packages/antson/icq-bot")
    ],
    [
        new CallbackButton("★☆☆☆☆", "callback1"),
        new CallbackButton("★★☆☆☆", "callback2"),
        new CallbackButton("★★★☆☆", "callback3"),
        new CallbackButton("★★★★☆", "callback4"),
        new CallbackButton("★★★★★", "callback5"),
    ],
    [
        new UrlButton("API DOC", "https://icq.com/botapi"),
        new UrlButton("Tutorial", "https://icq.com/botapi/botTutorial.html")
    ]
]);
show_debug($keyboard3, "одним параметром");

// помоему таких вариантов достаточно

/**
 * @param InlineKeyboard $keyboard
 * @param string $caption
 */
function show_debug($keyboard, $caption)
{
    echo "<h1>" . $caption . "</h1>";
    $p = $keyboard->toApiParam();
    echo '<textarea rows=10 style="width:100%">' . $p . '</textarea><br>';
    echo '<pre style="background:#f0f0f0;padding:16px">';
    print_r(json_decode($p));
    echo '</pre>';
}

// ==============================================================================
//  кнопки к сообщению
// ==============================================================================
try {
    $icq = new Antson\IcqBot\Client(TOKEN);

    $result = $icq->sendText(DEBUG_UIN, 'Buttons',null,null,null,$keyboard3);
} catch (Exception $e) {
    echo $e->getMessage();
}