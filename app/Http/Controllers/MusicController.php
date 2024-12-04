<?php

// app/Http/Controllers/MusicController.php

namespace App\Http\Controllers;

use App\Services\MusicScanner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    protected $musicScanner;

    public function __construct(MusicScanner $musicScanner)
    {
        $this->musicScanner = $musicScanner;
    }

    public function showMusicFiles()
    {
        // Retrieve cached music files
        $allMusicFiles = Cache::get('music_files', []);

        // Debugging output
        if (empty($allMusicFiles)) {
            Log::info('No music files found in cache.');
        } else {
            Log::info('Music files retrieved from cache.');
        }

        return view('welcome', compact('allMusicFiles'));
    }

    public function openFolder(Request $request)
    {
        $folderPath = $request->query('path');

        if (file_exists($folderPath)) {
            // For macOS
            if (PHP_OS_FAMILY === 'Darwin') {
                exec("open " . escapeshellarg($folderPath));
            }
            // For Windows
            elseif (PHP_OS_FAMILY === 'Windows') {
                exec("explorer " . escapeshellarg($folderPath));
            }
            // For Linux (using xdg-open)
            elseif (PHP_OS_FAMILY === 'Linux') {
                exec("xdg-open " . escapeshellarg($folderPath));
            }
        }

        return redirect()->back();
    }
}
