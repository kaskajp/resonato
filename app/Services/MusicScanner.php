<?php
// app/Services/MusicScanner.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MusicScanner
{
    public function scanAllDrives()
    {
        $externalDrives = json_decode(env('EXTERNAL_DRIVES', '[]'), true);
        Log::info('Scanning drives:', ['drives' => $externalDrives]);
        $allMusicFiles = [];

        foreach ($externalDrives as $drive) {
            if (!empty($drive['path']) && !empty($drive['identifier'])) {
                $allMusicFiles = array_merge($allMusicFiles, $this->scanMusicDirectory($drive['path'], $drive['identifier']));
            }
        }

        // Cache the results
        Cache::forever('music_files', $allMusicFiles, 60 * 24); // Cache for 24 hours

        return $allMusicFiles;
    }

    public function scanMusicDirectory($directory, $driveIdentifier)
    {
        $musicFiles = [];
        $allowedExtensions = ['mp3', 'wav', 'flac', 'm4a', 'aac', 'ogg'];

        if (is_dir($directory)) {
            $files = scandir($directory);

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $filePath = $directory . DIRECTORY_SEPARATOR . $file;

                if (is_dir($filePath)) {
                    $musicFiles = array_merge($musicFiles, $this->scanMusicDirectory($filePath, $driveIdentifier));
                } else {
                    $fileInfo = pathinfo($filePath);
                    if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $allowedExtensions)) {
                        $artistName = basename(dirname(dirname($filePath))); // Get the artist name
                        $albumName = basename(dirname($filePath)); // Get the album name

                        // Check for cover images
                        $coverPath = $this->findCoverImage(dirname($filePath));

                        $musicFiles[] = [
                            'path' => $filePath,
                            'title' => $fileInfo['basename'],
                            'album' => $albumName,
                            'artist' => $artistName,
                            'cover' => $coverPath,
                            'drive_number' => $driveIdentifier
                        ];
                    }
                }
            }
        }

        return $musicFiles;
    }

    private function findCoverImage($directory)
    {
        $coverExtensions = ['jpg', 'jpeg', 'png'];
        foreach ($coverExtensions as $extension) {
            $coverPath = $directory . DIRECTORY_SEPARATOR . 'cover.' . $extension;
            if (file_exists($coverPath)) {
                return $coverPath;
            }
        }
        return null;
    }
}
