<?php

// URL сайта или сайтов, который нужно проверять
$urls = [
    'https://site.one',
    'https://site.two',
   
];

// Токен вашего бота и chat_id группы
$botToken = "";
$chatId = "";

// Функция проверки доступности сайта
function checkSiteAvailability($url) {
    // Инициализация cURL
    $ch = curl_init($url);
    
    // Установка параметров cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    // Выполнение запроса
    curl_exec($ch);
    
    // Получение HTTP-кода ответа
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Закрытие cURL
    curl_close($ch);
    
    // Возвращаем HTTP-код ответа
    return $http_code;
}

// Функция отправки уведомления в Telegram-группу
function sendTelegramNotification($botToken, $chatId, $message) {
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=".urlencode($message);

    // Отправка запроса в Telegram API
    file_get_contents($url);
}

// Основная логика для проверки всех сайтов
foreach ($urls as $url) {
    $http_code = checkSiteAvailability($url);

    // Проверяем, чтобы HTTP-код не был 200 (успех), 301 (редирект) или 302 (временный редирект)
    if ($http_code != 200 && $http_code != 301 && $http_code != 302) {
        $message = "Сайт $url недоступен! Код ошибки: $http_code";
        sendTelegramNotification($botToken, $chatId, $message);
        echo $message . "\n";
    } else {
        echo "Сайт $url доступен или перенаправляет. Код: $http_code\n";
    }
}
//Khilinski Valery
