<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoDownload extends Model
{
    // 👇 ЭТОТ МАССИВ РАЗРЕШАЕТ ЗАПОЛНЕНИЕ ПОЛЕЙ
    protected $fillable = [
        'user_id',
        'url',
        'title',
        'quality',
        'format',
    ];

    // Связь с моделью пользователя (чтобы можно было получить имя)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
