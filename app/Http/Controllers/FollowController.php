<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Follow;
use Exception;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        try {
            $follows = Follow::with('artist')
                        ->where('user_id', auth()->id())
                        ->where('is_followed', true)
                        ->orderBy('id','desc')
                        ->paginate($request->per_page ?? 10);

            return $this->sendResponse($follows, "Follow list retrieved successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

    public function createFollow($artistId)
    {
        try {
            $artist = Artist::find($artistId);
            if (!$artist) {
                return $this->sendError("Artist not found.");
            }

            $existingFollow = Follow::where('user_id', auth()->id())
                                    ->where('artist_id', $artistId)
                                    ->first();

            if ($existingFollow && $existingFollow->is_followed) {
                return $this->sendError("Artist is already followed.");
            }

            $follow = Follow::updateOrCreate(
                ['user_id' => auth()->id(), 'artist_id' => $artistId],
                ['is_followed' => true]
            );
            if($artist){
                $artist->is_followed = true;
                $artist->save();
            }
            return $this->sendResponse($follow, "Artist followed successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function unfollow($artistId)
    {
        try {
            $artist = Artist::find($artistId);
            if (!$artist) {
                return $this->sendError("Artist not found.");
            }
            $follow = Follow::where('user_id', auth()->id())
                            ->where('artist_id', $artistId)
                            ->first();
            if (!$follow || !$follow->is_followed) {
                return $this->sendError("You are not following this artist.");
            }
            $follow->is_followed = false;
            $follow->save();

            $artist->is_followed = false;
            $artist->save();

            return $this->sendResponse($follow, "Artist unfollowed successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }


}
