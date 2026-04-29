<?php

// debug_download.php

$url = 'https://youtu.be/sdpaqoveghk?si=cTK7yh1lGWB0wc5s'; // Вставьте сюда ссылку, которую вы тестировали вручную
$ytDlpPath = 'E:\\GithubWorks\\ProjectGlichChunk\\public\\yt-dlp.exe';
$downloadPath = __DIR__ . '\\downloads'; // Упрощаем путь для теста

// Формируем команду, которую должен выполнить PHP
// Используем двойные кавычки вокруг путей на случай, если в них есть пробелы
$command = "\"$ytDlpPath\" \"$url\" --output \"$downloadPath\\%(title)s.%(ext)s\" 2>&1";

echo "Выполняемая команда: <pre>$command</pre><hr>";

// Выполняем команду и захватываем весь вывод и ошибки (2>&1)
$output = shell_exec($command);

echo "<h2>Вывод команды:</h2><pre>$output</pre>";

if ($output === null) {
    echo "<h2>Ошибка PHP:</h2> Не удалось выполнить команду (shell_exec вернул NULL). Проверьте disable_functions в php.ini или права на выполнение $ytDlpPath.";
}

// Удалите этот файл после завершения отладки
