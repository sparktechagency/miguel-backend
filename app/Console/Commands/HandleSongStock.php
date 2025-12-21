<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Song;

class HandleSongStock extends Command
{
    protected $signature = 'songs:handle-stock';
    protected $description = 'Disable songs with Limited, Premium, Exclusive license when value is 0';

    public function handle()
    {
        $restrictedLicenses = ['Limited', 'Premium', 'Exclusive'];

        $songs = Song::where('value', '<=', 0)
            ->whereHas('license', function ($q) use ($restrictedLicenses) {
                $q->whereIn('name', $restrictedLicenses);
            })
            ->where('is_published', true)
            ->get();
         $count = $songs->count();
        foreach ($songs as $song) {
            $song->delete();
        }

       $this->info("{$count} songs deleted due to zero stock.");
    }
}
