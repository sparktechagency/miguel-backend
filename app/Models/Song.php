<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'title',
        'song_poster',
        'song',
        'artist_id',
         'genre_id',
         'bpm',
        'key_id',
        'license_id',
        'type_id',
        'gender',
        'price',
        'is_published',
        'is_wishlisted',
        'views',
        'is_topsong',
        'midi_file',
        'dry_vocals',
        'web_vocals',
        'lyrics'
    ];
    protected $casts = [
        'midi_file'  => 'array',
        'web_vocals' => 'array',
        'dry_vocals' => 'array',
        'is_published' => 'boolean',
        'is_topsong' => 'boolean',
    ];

    public function artist() {
         return $this->belongsTo(Artist::class);
        }
    public function genre() {
         return $this->belongsTo(Genre::class);
        }
    public function bpm() {
         return $this->belongsTo(BPM::class);
        }
    public function key() {
        return $this->belongsTo(Key::class); }
    public function license() { return $this->belongsTo(License::class);
     }
    public function type() {
        return $this->belongsTo(Type::class);
    }
}
