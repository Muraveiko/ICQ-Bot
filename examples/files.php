<pre><?php
require_once "../vendor/autoload.php";
require_once "config.php";

// Create a CURLFile object / procedural method
$cfile = curl_file_create(__DIR__.'/resources/tux.png','image/png','testpic.png'); // try adding

// Create a CURLFile object / oop method
#$cfile = new CURLFile('resource/test.png','image/png','testpic.png'); // uncomment and use if the upper procedural method is not working.

try {
    $icq = new Antson\IcqBot\Client(TOKEN);
    $result = $icq->sendNewFile(DEBUG_UIN,$cfile,'Новая загрузка '.Date("H:i"));
    if($result->isOk()){
        $fileId = $result->get_fileId();

        // зная ид загруженного файла его можно посылать повторно
        $result2 = $icq->sendPresentFile(DEBUG_UIN,$fileId,'Уже загружен');

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
