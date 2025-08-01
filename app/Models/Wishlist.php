<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable =[
        "user_id",
        "song_id",
        "is_wishlisted"
    ];

    public function song(){
        return $this->belongsTo(Song::class);
    }
    public function artist(){
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
    public function license()
    {
        return $this->belongsTo(License::class);
     }
    public function type() {
        return $this->belongsTo(Type::class);
    }
}
