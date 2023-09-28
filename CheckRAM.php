<?php
$availableRam_id = 0; $totalRam = 0; $availableRam = 0; $txt = "Скрипт проверки использования оперативной памяти запущен!
Работает только на ОС: Linux и Android";

#Отправка сообщений в Телеграм, ВК / Send message to Telegram, VK
function send(string $message = ''): void {
    $token = 'bot1234:TEST-test'; #токен бота / token bot
    $id = '-10071'; #айди чата / chat id

    $url = 'https://api.telegram.org/' . $token . '/sendMessage?chat_id=' . $id . '&text=' . urlencode($message);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    $url = 'https://api.vk.com/method/messages.send';
    $params = [
        'access_token' => 'vk1.a.', #токен / token
        'v' => '5.85'
    ];
    $params['message'] = $message;
    $params['chat_id'] = 1; #айди чата / id chat
    $ch = curl_init($url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
}

echo $txt;

#ЗАПУСК / START
while (true) {
    $info = @file_get_contents('/proc/meminfo');

     if (preg_match('/^MemTotal:\s+(\d+)\skB$/m', $info, $matches)) {
        $totalRam = round($matches[1] / 1024 / 1024, 2);
     }

     if (preg_match('/^MemAvailable:\s+(\d+)\skB$/m', $info, $matches)) {
        $availableRam = round($matches[1] / 1024 / 1024, 2);
     }

    if((int)$availableRam < 1){
        $message = date('d-m-Y H:i:s') . ' | 🔴 Не осталось свободного места в ОЗУ: ' . $availableRam . 'ГБ, было: ' . $availableRam_id . 'ГБ из ' . $totalRam . ' ГБ' . PHP_EOL;
        
         echo PHP_EOL . $message;
         send($message);
    }
    
    if ($availableRam !== $availableRam_id) {
        $message = date('d-m-Y H:i:s') . ' | Осталось свободного места в ОЗУ: ' . $availableRam . 'ГБ, было: ' . $availableRam_id . 'ГБ из ' . $totalRam . ' ГБ' . PHP_EOL;
        
         $availableRam_id = $availableRam;
         echo PHP_EOL . $message;
    }
}

sleep(2); #время проверки / time check
?>
