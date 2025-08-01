<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Wishlist;
use Exception;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function wishlist(Request $request)
    {
        try {
            $wishlist = Wishlist::with(['song','song.artist', 'song.genre', 'song.key', 'song.license', 'song.type'])
                        ->where('user_id', auth()->id())
                        ->where('is_wishlisted', true)
                        ->orderBy('id','desc')
                        ->paginate($request->per_page ?? 10);
            return $this->sendResponse($wishlist, "Wishlist retrieved successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function createWishlist($songId)
    {
        try {
            $song = Song::find($songId);
            if (!$song) {
                return $this->sendError("Song not found.");
            }
            $existingWishlist = Wishlist::where('user_id', auth()->id())
                                        ->where('song_id', $songId)
                                        ->where('is_wishlisted',true)
                                        ->first();
            if ($existingWishlist) {
                return $this->sendError("Song is already in your wishlist.");
            }
            $wishlist = Wishlist::create([
                'user_id' => auth()->id(),
                'song_id' => $songId,
                'is_wishlisted' => true,
            ]);
            if($song){
                $song->is_wishlisted = true;
                $song->save();
            }
            return $this->sendResponse($wishlist, "Wishlist created successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function removeWishlist($songId)
    {
        try {
            $song = Song::find($songId);
            if (!$song) {
                return $this->sendError("Song not found.");
            }
            $wishlist = Wishlist::where('user_id', auth()->id())
                                ->where('song_id', $songId)
                                ->where('is_wishlisted', true)
                                ->first();
            if (!$wishlist) {
                return $this->sendError("Song is not in the wishlist.");
            }
            $song->is_wishlisted = false;
            $song->save();
            $wishlist->is_wishlisted = false;
            $wishlist->save();
            return $this->sendResponse($wishlist, "Song removed from wishlist.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
