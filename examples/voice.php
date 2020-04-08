<pre><?php
// Голосовые сообщения похожи на файлы, но
// eсли вы хотите, чтобы клиент отображал этот файл как воспроизводимое голосовое сообщение, он должен быть в формате aac, ogg или m4a.

require_once "../vendor/autoload.php";
require_once "config.php";

// Create a CURLFile object / procedural method
$cfile = curl_file_create(__DIR__.'/resources/test.aac','audio/aac','hello.acc'); // try adding

// Create a CURLFile object / oop method
#$cfile = new CURLFile('resource/test.png','image/png','testpic.png'); // uncomment and use if the upper procedural method is not working.

try {
    $icq = new Antson\IcqBot\Client(TOKEN);
    $result = $icq->sendNewVoice(DEBUG_UIN,$cfile);
    if($result->isOk()){
        $fileId = $result->get_fileId();

        // зная ид загруженного файла его можно посылать повторно
        $result2 = $icq->sendPresentVoice(DEBUG_UIN,$fileId);

        // можно запросить информацию о файле

        $info = $icq->fileGetInfo($fileId);
        echo $info->get_type()."\n";
        echo $info->get_filename()."\n";
        echo $info->get_size()."\n";
        echo $info->get_url()."\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

