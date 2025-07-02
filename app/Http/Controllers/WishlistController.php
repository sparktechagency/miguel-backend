<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Wishlist;
use Exception;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function wishlist(Request $request)
    {
        try {
            $wishlist = Wishlist::with('artist')
                        ->where('user_id', auth()->id())
                        ->where('is_wishlisted', true)
                        ->orderBy('id','desc')
                        ->paginate($request->per_page ?? 10);
            return $this->sendResponse($wishlist, "Wishlist retrieved successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function createWishlist($artistId)
    {
        try {
            $artist = Artist::find($artistId);
            if (!$artist) {
                return $this->sendError("Artist not found.");
            }

            $existingWishlist = Wishlist::where('user_id', auth()->id())
                                        ->where('artist_id', $artistId)
                                        ->where('is_wishlisted',true)
                                        ->first();

            if ($existingWishlist) {
                return $this->sendError("Artist is already in your wishlist.");
            }

            $wishlist = Wishlist::create([
                'user_id' => auth()->id(),
                'artist_id' => $artistId,
                'is_wishlisted' => true,
            ]);
            if($artist){
                $artist->is_wishlisted = true;
                $artist->save();
            }
            return $this->sendResponse($wishlist, "Wishlist created successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function removeWishlist($artistId)
    {
        try {
            $artist = Artist::find($artistId);
            if (!$artist) {
                return $this->sendError("Artist not found.");
            }

            $wishlist = Wishlist::where('user_id', auth()->id())
                                ->where('artist_id', $artistId)
                                ->where('is_wishlisted', true)
                                ->first();

            if (!$wishlist) {
                return $this->sendError("Artist is not in the wishlist.");
            }

            // If artist-wide flag is needed (optional)
            $artist->is_wishlisted = false;
            $artist->save();

            // Update user's wishlist flag
            $wishlist->is_wishlisted = false;
            $wishlist->save();

            return $this->sendResponse($wishlist, "Artist removed from wishlist.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }


}
