<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    public function downloads(Request $request)
    {
        set_time_limit(600); // Чтобы не было 504 на длинных видео

        $url = $request->input('url');
        $format = $request->input('format');
        $quality = $request->input('quality');

        $downloadDir = storage_path('app/public/downloads');
        $tempName = \Illuminate\Support\Str::random(10);
        $outputPath = $downloadDir . '/' . $tempName . '.%(ext)s';

        if ($format === 'mp3' || $format === 'wav') {
            // ЛОГИКА АУДИО
            $params = "-x --audio-format $format --audio-quality 0";
        } else {
            // ЛОГИКА ВИДЕО
            $hasAudio = str_contains($quality, '_audio');
            $height = str_replace(['_audio', '_noaudio'], '', $quality);
            $heightLimit = ($height === 'best') ? '2160' : $height;

            if ($hasAudio) {
                // Видео + Звук
                $params = "-f \"bestvideo[height<=$heightLimit][ext=$format]+bestaudio[ext=m4a]/best[ext=$format]/best\" --merge-output-format $format";
            } else {
                // Только Видео (без звука)
                $params = "-f \"bestvideo[height<=$heightLimit][ext=$format]/bestvideo[height<=$heightLimit]\"";
            }
        }

        $command = "yt-dlp " . escapeshellarg($url) . " $params -o " . escapeshellarg($outputPath) . " --ffmpeg-location /usr/bin/ffmpeg 2>&1";
        exec($command);

        $videoTitle = shell_exec("yt-dlp --get-title --no-playlist " . escapeshellarg($url));
        // Очищаем название от странных символов для файловой системы
        $safeName = \Illuminate\Support\Str::slug(trim($videoTitle ?: 'video')) ?: 'TutaGlitch_file';

        // 2. Ищем скачанный файл (наш временный cYYZJ...)
        $files = glob($downloadDir . '/' . $tempName . '.*');

        if (!empty($files)) {
            $filePath = $files[0]; // Берем первый найденный файл
            $extension = pathinfo($filePath, PATHINFO_EXTENSION); // Получаем расширение (mp4, mp3 и т.д.)

            // Формируем красивое имя: Название_Видео.расширение
            $finalFileName = $safeName . '.' . $extension;

            // Отдаем пользователю с КРАСИВЫМ именем
            return response()->download($filePath, $finalFileName)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Ошибка скачивания');
    }
}


