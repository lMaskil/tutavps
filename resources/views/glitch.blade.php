@php
    if (!isset($downloads)) {
        $downloads = collect();
    }
@endphp

@extends('layouts.main')

@section('content1')
    <div class="d-flex gap-4 align-items-center">
        @auth
            {{-- Кнопка-кружок с первой буквой имени --}}
            <a href="#" class="btn btn-outline-light text-white px-4 py-2 fs-4 border-2 fw-bold"
               style="width: 50px; height: 50px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Выйти">
                {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
            </a>

            {{-- Скрытая форма для выхода --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @else
            {{-- Кнопки для гостей --}}
            <a href="{{ route('login.index') }}" class="text-secondary text-decoration-none fw-bold fs-4">Login</a>
            <a href="{{ route('register.index') }}" class="text-secondary text-decoration-none fw-bold fs-4">Register</a>
            <a href="{{ route('login.index') }}" class="btn btn-outline-light text-white px-4 py-2 fs-4 border-2">User</a>
        @endauth
    </div>
@endsection

@section('content2')
    <div class="row pt-5 gx-4">

        {{-- ЛЕВАЯ КОЛОНКА: ФОРМА СКАЧИВАНИЯ (Авторизован -> 8 колонок, Гость -> 12 колонок) --}}
        <div class="@auth col-lg-8 @else col-lg-12 @endauth mb-4">
            <div class="p-5 rounded-5 h-100" style="background-color: rgba(0,0,0,0.85); border: 1px solid #222; box-shadow: 0 0 50px rgba(0,0,0,0.8);">

                {{-- Вывод ошибок скачивания --}}
                @if(session('error'))
                    <div class="alert alert-danger bg-danger text-white border-0 mb-4 text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <h2 class="text-secondary opacity-50 mb-4 fw-light text-uppercase text-center" style="letter-spacing: 5px;">Download Video</h2>

                {{-- Форма отправки --}}
                <form action="{{ route('video.download') }}" method="POST" id="downloadForm">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="url" class="form-control bg-dark text-white border-secondary py-2" placeholder="Вставьте ссылку..." required>

                        <select name="quality" id="qualitySelect" class="form-select bg-dark text-secondary border-secondary py-2" style="max-width: 170px;">
                            <option value="best_audio">Best Video + Sound</option>
                            <option value="1080_audio">1080p + Sound</option>
                            <option value="720_audio">720p + Sound</option>
                            <option value="best_noaudio">Best Video (No Sound)</option>
                            <option value="1080_noaudio">1080p (No Sound)</option>
                            <option value="720_noaudio">720p (No Sound)</option>
                        </select>

                        <select name="format" id="formatSelect" class="form-select bg-dark text-secondary border-secondary py-2" style="max-width: 120px;">
                            <optgroup label="Video" class="bg-dark text-white">
                                <option value="mp4" selected>MP4</option>
                                <option value="webm">WebM</option>
                            </optgroup>
                            <optgroup label="Audio" class="bg-dark text-white">
                                <option value="mp3">MP3</option>
                                <option value="wav">WAV</option>
                            </optgroup>
                        </select>

                        <button id="submitBtn" class="btn px-4 fw-bold text-light shadow-sm py-2" style="background-color: #2c3e50; border: 1px solid #1a252f;" type="submit">Скачать</button>
                    </div>
                </form>

                {{-- Полоса загрузки --}}
                <div id="progressWrapper" class="mt-4 d-none">
                    <div class="text-secondary mb-2 text-center opacity-75">
                        <span id="statusMessage">⌛ Обработка и скачивание...</span> <span id="percentText">0%</span>
                    </div>
                    <div class="progress bg-dark" style="height: 10px; border-radius: 5px; border: 1px solid #222;">
                        <div id="progressBar" class="progress-bar" style="width: 0%; background-color: #2c3e50; transition: width 0.5s ease;"></div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ПРАВАЯ КОЛОНКА: ИСТОРИЯ (Отображается ТОЛЬКО для авторизованных) --}}
        @auth
            <div class="col-lg-4 mb-4">
                <div class="p-4 rounded-5 h-100" style="background-color: rgba(0,0,0,0.85); border: 1px solid #222; box-shadow: 0 0 50px rgba(0,0,0,0.8);">

                    <h3 class="text-secondary opacity-50 mb-4 fw-light text-uppercase text-center" style="letter-spacing: 3px; font-size: 1.2rem;">Последние скачивания</h3>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0" style="background-color: rgba(0,0,0,0.5); border: 1px solid #222; font-size: 0.85rem;">
                            <thead>
                            <tr class="text-secondary opacity-75">
                                <th class="border-secondary">Видео</th>
                                <th class="border-secondary">Юзер</th>
                                <th class="border-secondary">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($downloads as $download)
                                <tr>
                                    <td class="border-secondary text-white-50 text-truncate" style="max-width: 120px;" title="{{ $download->title }}">
                                        {{ $download->title ?? 'Без названия' }}
                                    </td>
                                    <td class="border-secondary text-white-50">
                                        {{ $download->user ? $download->user->name : 'Гость' }}
                                    </td>
                                    <td class="border-secondary">
                                        <span class="badge" style="background-color: #2c3e50;">{{ strtoupper($download->format) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-secondary py-3 border-secondary">История пока пуста</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endauth

    </div>

    {{-- Скрипт переключения качества и полосы загрузки --}}
    <script>
        const formatSelect = document.getElementById('formatSelect');
        const qualitySelect = document.getElementById('qualitySelect');
        const downloadForm = document.getElementById('downloadForm');
        const btn = document.getElementById('submitBtn');
        const wrapper = document.getElementById('progressWrapper');
        const bar = document.getElementById('progressBar');
        const percentText = document.getElementById('percentText');

        formatSelect.addEventListener('change', function() {
            qualitySelect.innerHTML = '';
            if (this.value === 'mp3' || this.value === 'wav') {
                const opts = [{v:'best',t:'Best Audio'},{v:'320',t:'320 kbps'},{v:'128',t:'128 kbps'}];
                opts.forEach(o => qualitySelect.innerHTML += `<option value="${o.v}">${o.t}</option>`);
            } else {
                const opts = [
                    {v:'best_audio',t:'Best Video + Sound'},{v:'1080_audio',t:'1080p + Sound'},{v:'720_audio',t:'720p + Sound'},
                    {v:'best_noaudio',t:'Best Video (No Sound)'},{v:'1080_noaudio',t:'1080p (No Sound)'},{v:'720_noaudio',t:'720p (No Sound)'}
                ];
                opts.forEach(o => qualitySelect.innerHTML += `<option value="${o.v}">${o.t}</option>`);
            }
        });

        downloadForm.addEventListener('submit', function() {
            btn.disabled = true;
            btn.innerHTML = '⚡ Ждите...';
            wrapper.classList.remove('d-none');

            let width = 0;
            const interval = setInterval(() => {
                if (width >= 90) {
                    clearInterval(interval);
                } else {
                    width += Math.random() * 3;
                    if (width > 90) width = 90;
                    bar.style.width = width + '%';
                    percentText.innerHTML = Math.round(width) + '%';
                }
            }, 1000);

            // Ожидание 45 секунд во избежание сброса до окончания генерации файла
            setTimeout(() => {
                clearInterval(interval);
                bar.style.width = '100%';
                percentText.innerHTML = '100%';

                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = 'Скачать';
                    wrapper.classList.add('d-none');
                    bar.style.width = '0%';
                    percentText.innerHTML = '0%';
                }, 2000);

            }, 45000);
        });
    </script>
@endsection
