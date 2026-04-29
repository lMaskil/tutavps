<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoDownload;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index() {
        $downloads = VideoDownload::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('glitch', compact('downloads'));
    }
}
