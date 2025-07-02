<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use App\Models\Song;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function artistDetail($artistId)
    {
        try {
            $artist = Artist::find($artistId);
            if (!$artist) {
                return $this->sendError("Artist not found.");
            }
            $songs = Song::with(['artist', 'genre', 'key', 'license', 'type'])
                        ->where('artist_id', $artistId)
                        ->orderBy('id','desc')
                        ->get();
            $artist->songs_count = $songs->count();
            $response = [
                'artist' => $artist,
                'songs'  => $songs
            ];
            return $this->sendResponse($response,"Artist Details retrived successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function artist(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $artists = Artist::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->orderBy('id','desc')->paginate($perPage);

        return $this->sendResponse($artists, 'Artist list fetched successfully.');
    }
    public function createArtist(ArtistRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('profile')) {
                $image = $request->file('profile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/artists', $imageName, 'public');
                $data['profile'] = 'storage/' . $path;
            }

            $artist = Artist::create($data);
            return $this->sendResponse($artist, 'Artist created successfully.');
        } catch (Exception $e) {
            return $this->sendError("Error: " . $e->getMessage(), [], 500);
        }
    }

    public function updateArtist(ArtistRequest $artistRequest, $artistId)
    {
        try {
            $artist = Artist::find($artistId);
            if(!$artist){
                return $this->sendError("Artist not found.");
            }
            $artist->fill($artistRequest->only(['name', 'description', 'profile', 'gender','singer','singer_writer','location','is_wishlisted','is_followed']));
            if ($artistRequest->hasFile('profile')) {
                if ($artist->profile && Storage::disk('public')->exists(str_replace('storage/', '', $artist->profile))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $artist->profile));
                }
                $image = $artistRequest->file('profile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/artists', $imageName, 'public');
                $artist->profile = 'storage/' . $path;
            }

            $artist->save();

            return $this->sendResponse($artist, 'Artist updated successfully.');
        } catch (Exception $e) {
            return $this->sendError("Error: " . $e->getMessage(), [], 500);
        }
    }
    public function deleteArtist($artistId)
    {
        try {
            $artist = Artist::findOrFail($artistId);
            if ($artist->profile && $artist->profile !== 'default/user.png') {
                $path = str_replace('storage/', '', $artist->profile);
                Storage::disk('public')->delete($path);
            }
            $artist->delete();
            return $this->sendResponse([], 'Artist deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError("Error: " . $e->getMessage(), [], 500);
        }
    }

}
