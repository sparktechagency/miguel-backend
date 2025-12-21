<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;

class HandleSongStock extends Command
{
    protected $signature = 'songs:handle-stock';
    protected $description = 'Delete Limited, Premium, Exclusive songs when value is 0 and remove all files';

    public function handle()
    {
        $restrictedLicenses = ['Limited', 'Premium', 'Exclusive'];

        $songs = Song::where('value', '<=', 0)
            ->whereHas('license', function ($q) use ($restrictedLicenses) {
                $q->whereIn('name', $restrictedLicenses);
            })
            ->get();

        $count = $songs->count();

        foreach ($songs as $song) {
            $this->deleteSongFiles($song);
            $song->delete();
        }

        $this->info("{$count} songs and all files deleted due to zero stock.");
    }

    private function deleteSongFiles(Song $song)
    {
        // 🎵 Main song
        if ($song->song && Storage::disk('public')->exists($song->song)) {
            Storage::disk('public')->delete($song->song);
        }

        // 🖼 Poster
        if ($song->song_poster && Storage::disk('public')->exists($song->song_poster)) {
            Storage::disk('public')->delete($song->song_poster);
        }

        // 📄 Lyrics
        if ($song->lyrics && Storage::disk('public')->exists($song->lyrics)) {
            Storage::disk('public')->delete($song->lyrics);
        }

        // 🎹 MIDI files
        if (is_array($song->midi_file)) {
            foreach ($song->midi_file as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // 🎤 Web vocals
        if (is_array($song->web_vocals)) {
            foreach ($song->web_vocals as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // 🎧 Dry vocals
        if (is_array($song->dry_vocals)) {
            foreach ($song->dry_vocals as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // 📁 Optional: delete song folder
        if ($song->folder_path && Storage::disk('public')->exists($song->folder_path)) {
            Storage::disk('public')->deleteDirectory($song->folder_path);
        }
    }
}
