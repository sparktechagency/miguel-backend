<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use App\Models\Song;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class ArtistController extends Controller
{
    public function artistDetail($slug)
    {
        try {
            $artist = Artist::where('slug',$slug)->first();
            if (!$artist) {
                return $this->sendError("Artist not found.");
            }
            $songs = Song::with(['artist', 'genre', 'key', 'license', 'type'])
                        ->where('artist_id', $artist->id)
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
        $gender = $request->query('gender');
        $language = $request->query('language');
        $perPage = $request->query('per_page', 10);

        $artists = Artist::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($gender, function ($query, $gender) {
                $query->where('gender', $gender);
            })
            ->when($language, function ($query, $language) {
                $query->where('language', $language);
            })
            ->orderBy('is_topartist','desc')
            ->paginate($perPage);

        return $this->sendResponse($artists, 'Artist list fetched successfully.');
    }

    public function createArtist(ArtistRequest $request)
    {
        try {
            $data = $request->validated();
            $slug = Str::slug($data['name']) . '-' . uniqid();
            $data['slug'] = $slug;
            if ($request->hasFile('profile')) {
                $image = $request->file('profile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/artists', $imageName, 'public');
                $data['profile'] = 'storage/' . $path;
            }
            if ($request->hasFile('cover_song')) {
                $file = $request->file('cover_song');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/songs', $fileName, 'public');
                $data['cover_song'] = 'storage/' . $path;
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
            if ($artist->name !== $artistRequest->name) {
                $slug = Str::slug($artistRequest->name) . '-' . uniqid();
                $artistRequest->merge(['slug' => $slug]);
            }
            $artist->fill($artistRequest->only(
                ['name','slug','description', 'profile', 'gender','singer','singer_writer','location','is_wishlisted','is_followed','language','cover_song','price']));
            if ($artistRequest->hasFile('profile')) {
                if ($artist->profile && Storage::disk('public')->exists(str_replace('storage/', '', $artist->profile))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $artist->profile));
                }
                $image = $artistRequest->file('profile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/artists', $imageName, 'public');
                $artist->profile = 'storage/' . $path;
            }
            if ($artistRequest->hasFile('cover_song')) {
                    if ($artist->cover_song && Storage::disk('public')->exists(str_replace('storage/', '', $artist->cover_song))) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $artist->cover_song));
                    }
                    $cover_song = $artistRequest->file('cover_song');
                    $fileName = time() . '.' . $cover_song->getClientOriginalExtension();
                    $path = $cover_song->storeAs('uploads/cover_song', $fileName, 'public');
                    $artist->cover_song = 'storage/' . $path;
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
          return   $artist = Artist::findOrFail($artistId);
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
    public function topArtist($artistId)
    {
        try {
            $artist = Artist::findOrFail($artistId);
            if(!$artist){
                return $this->sendError("Artist not found.");
            }
            $artist->is_topartist = !$artist->is_topartist;
            $artist->save();
            return $this->sendResponse($artist, 'Top artist updated successfully.');
        } catch (Exception $e) {
            return $this->sendError("Error: " . $e->getMessage(), [], 500);
        }
    }
}
