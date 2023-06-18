<?php
$time = 2; //second timer
$availableRam_id = 0;
$totalRam = 0;
$availableRam = 0;

function send(string $msg = ''): void {
    $idChatTG = 0;
    $idChatVK = 0;
    $keyBotTG = 'bot58:an';

    //TG
    $url = 'https://api.telegram.org/' . (string)$keyBotTG . '/sendMessage?chat_id=' . (int)$idChatTG . '&text=' . urlencode($msg);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    //VK
    $url = 'https://api.vk.com/method/messages.send';
    $params = [
        'access_token' => 'vk1.a.',
        //key group
        'v' => '5.85'
    ];
    $params['message'] = $msg;
    $params['chat_id'] = (int)$idChatVK;
    $ch = curl_init($url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
}

echo 'Скрипт проверки использования оперативной памяти запущен!
Работает только на ОС: Linux и Android' . PHP_EOL;
while (true) {
    sleep($time);
    
    $info = @file_get_contents('/proc/meminfo');

    if (preg_match('/^MemTotal:\s+(\d+)\skB$/m', $info, $matches)) {
        $totalRam = round($matches[1] / 1024 / 1024, 2);
    }

    if (preg_match('/^MemAvailable:\s+(\d+)\skB$/m', $info, $matches)) {
        $availableRam = round($matches[1] / 1024 / 1024, 2);
    }

    $totalRam = $totalRam;
    $availableRam = $availableRam;
    
    if ($availableRam !== $availableRam_id) {
        $message = date('d-m-Y H:i:s') . ' | Осталось свободного места в ОЗУ: ' . $availableRam . 'ГБ, было: ' . $availableRam_id . 'ГБ из ' . $totalRam . ' ГБ' . PHP_EOL;
        
         $availableRam_id = $availableRam;
         echo $message;
         send($message);
    }
}
?>
