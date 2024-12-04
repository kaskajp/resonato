<?php

namespace App\Console\Commands;

   use Illuminate\Console\Command;
   use App\Services\MusicScanner;

   class ScanMusic extends Command
   {
       protected $signature = 'music:scan';
       protected $description = 'Scan music files from external drives';

       protected $musicScanner;

       public function __construct(MusicScanner $musicScanner)
       {
           parent::__construct();
           $this->musicScanner = $musicScanner;
       }

       public function handle()
       {
           set_time_limit(0); // No time limit for CLI
           $allMusicFiles = $this->musicScanner->scanAllDrives();
           $this->info('Music files scanned successfully.');
           return Command::SUCCESS;
       }
   }
