<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\VideoDownload;
use Illuminate\Support\Facades\Auth; // Добавили явный импорт
use Illuminate\Support\Facades\Log;  // Добавили явный импорт

class DownloadController extends Controller
{
    public function downloads(Request $request)
    {
        set_time_limit(600); // Чтобы не было 504 на длинных видео

        $url = $request->input('url');
        $format = $request->input('format');
        $quality = $request->input('quality');

        $downloadDir = storage_path('app/public/downloads');
        $tempName = Str::random(10);
        $outputPath = $downloadDir . '/' . $tempName . '.%(ext)s';

        if ($format === 'mp3' || $format === 'wav') {
            $params = "-x --audio-format $format --audio-quality 0";
        } else {
            $hasAudio = str_contains($quality, '_audio');
            $height = str_replace(['_audio', '_noaudio'], '', $quality);
            $heightLimit = ($height === 'best') ? '2160' : $height;

            if ($hasAudio) {
                $params = "-f \"bestvideo[height<=$heightLimit][ext=$format]+bestaudio[ext=m4a]/best[ext=$format]/best\" --merge-output-format $format";
            } else {
                $params = "-f \"bestvideo[height<=$heightLimit][ext=$format]/bestvideo[height<=$heightLimit]\"";
            }
        }

        $command = "yt-dlp " . escapeshellarg($url) . " $params -o " . escapeshellarg($outputPath) . " -4 --no-check-certificate --ffmpeg-location /usr/bin/ffmpeg 2>&1";
        exec($command, $output, $returnCode);

        $videoTitle = shell_exec("yt-dlp -4 --no-check-certificate --get-title --no-playlist " . escapeshellarg($url));
        $safeName = Str::slug(trim($videoTitle ?: 'video')) ?: 'TutaGlitch_file';

        $files = glob($downloadDir . '/' . $tempName . '.*');

        if (!empty($files)) {
            $filePath = $files[0];
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $finalFileName = $safeName . '.' . $extension;

            // 👇 ПРОВЕРЯЕМ СЕССИЮ ПЕРЕД СОХРАНЕНИЕМ И ПИШЕМ В ЛОГ
            $userId = Auth::id();
            Log::info("Скачивание начато. ID авторизованного пользователя: " . ($userId ?? 'NULL (Гость)'));

            VideoDownload::create([
                'user_id' => $userId, // Сохранится ID пользователя или null
                'url' => $url,
                'title' => trim($videoTitle) ?: 'Видео без названия',
                'quality' => $quality,
                'format' => $format,
            ]);

            return response()->download($filePath, $finalFileName)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Ошибка скачивания');
    }
}


