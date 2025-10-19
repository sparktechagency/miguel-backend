<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongRequest;
use App\Models\Song;
use App\Models\User;
use App\Notifications\PublishSongNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
  public function song(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $query = Song::where('is_published', true)
                ->with(['artist', 'genre', 'key', 'license', 'type']);

            // Apply filters
            $this->applyMultiFilter($query, 'artist_id', $request->artist_id);
            $this->applyMultiFilter($query, 'genre_id', $request->genre_id);
            $this->applyMultiFilter($query, 'key_id', $request->key_id);
            $this->applyMultiFilter($query, 'license_id', $request->license_id);
            $this->applyMultiFilter($query, 'type_id', $request->type_id);
            $this->applyMultiFilter($query, 'gender', $request->gender);
            $this->applyMultiLanguageFilter($query, 'language', $request->language);

            if ($request->has('bpm_value')) {
                $query->where('bpm', $request->bpm_value);
            }

            // Search filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('gender', 'like', "%$searchTerm%")
                    ->orWhere('price', 'like', "%$searchTerm%")
                    ->orWhere('bpm', 'like', "%$searchTerm%")
                    ->orWhereHas('artist', fn($q) => $q->where('name', 'like', "%$searchTerm%"))
                    ->orWhereHas('genre', fn($q) => $q->where('name', 'like', "%$searchTerm%"))
                    ->orWhereHas('key', fn($q) => $q->where('name', 'like', "%$searchTerm%"))
                    ->orWhereHas('license', fn($q) => $q->where('name', 'like', "%$searchTerm%"))
                    ->orWhereHas('type', fn($q) => $q->where('name', 'like', "%$searchTerm%"));
                });
            }

            $songs = $query->orderByDesc('is_topsong')
                        ->orderByDesc('views')
                        ->paginate($perPage);

            return response()->json(['success' => true, 'data' => $songs]);
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

    private function applyMultiFilter(&$query, $field, $value)
    {
        if (!is_null($value)) {
            is_array($value)
                ? $query->whereIn($field, $value)
                : $query->where($field, $value);
        }
    }

    private function applyMultiLanguageFilter(&$query, $field, $value)
    {
        if (!empty($value)) {
            $query->whereHas('artist', function ($q) use ($field, $value) {
                $q->where($field, 'like', '%' . $value . '%');
            });
        }
    }

    public function publishSong(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $query = Song::with(['artist', 'genre', 'key', 'license', 'type']);
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    $q->orWhere('gender', 'like', '%' . $searchTerm . '%')
                      ->orWhere('price', 'like', '%' . $searchTerm . '%')
                      ->orWhere('bpm', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('artist', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('genre', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('key', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('license', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('type', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            $songs = $query->orderBy('id', 'desc')->paginate($perPage);
            return response()->json(['success' => true, 'data' => $songs]);
        } catch (Exception $e) {
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function createSong(SongRequest $songRequest)
    {
        try {
            $validated = $songRequest->validated();
            if ($songRequest->hasFile('song')) {
                $file = $songRequest->file('song');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/songs', $fileName, 'public');
                $validated['song'] = 'storage/' . $path;
            }
            if ($songRequest->hasFile('song_poster')) {
                $file = $songRequest->file('song_poster');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/song_posters', $fileName, 'public');
                $validated['song_poster'] = 'storage/' . $path;
            }
            $song = Song::create($validated);
            return $this->sendResponse($song,'Song created successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function updateSong(SongRequest $songRequest, $songId)
    {
        try{
            $song = Song::findOrFail($songId);
            $validated = $songRequest->validated();
            if ($songRequest->hasFile('song')) {
                $file = $songRequest->file('song');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/songs', $fileName, 'public');
                $validated['song'] = 'storage/' . $path;

                if ($song->song) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $song->song));
                }
            }
            if ($songRequest->hasFile('song_poster')) {
                $file = $songRequest->file('song_poster');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/song_posters', $fileName, 'public');
                $validated['song_poster'] = 'storage/' . $path;

                if($song->song_poster){
                    Storage::disk('public')->delete(str_replace('storage/', '', $song->song_poster));
                }
            }
            $song->update($validated);
            return $this->sendResponse( $song, 'Song updated successfully.');
        }catch(Exception $e){
            $this->sendError("An error occurrd: ".$e->getMessage(),[],500);
        }
    }
    public function published(Request $request, $songId)
    {
        try {
            $song = Song::find($songId);
            if(!$song){
                return $this->sendError("Song not found.");
            }
            $song->is_published = $request->is_published;
            $song->save();
            $message = $song->is_published ? 'Song published successfully' : 'Song unpublished successfully';
            $users = User::where('role', 'USER')->get();
            foreach ($users as $user) {
                $user->notify(new PublishSongNotification($song));
            }
            return $this->sendResponse($song, $message);
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
  public function deleteSong($songId)
    {
        try {
            $song = Song::findOrFail($songId);
            if ($song->song) {
                Storage::disk('public')->delete(str_replace('storage/', '', $song->song));
            }
            $song->delete();
            return $this->sendResponse([], 'Song deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function latestTrending($song_id)
    {
        try {
            $song = Song::find($song_id);
            if(!$song){
                return $this->sendError("Song not found.");
            }
           $song->increment('views');
            activity()
            ->causedBy(auth()->user())
            ->performedOn($song)
            ->log("Successfully fetched song with ID: {$song_id}");

             return $this->sendResponse([], 'Song activity added successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
  public function songDetails($songId)
    {
        try {
            $song = Song::with(['artist', 'genre', 'key', 'license', 'type'])->find($songId);
            if(!$song){
                return $this->sendError("Song not found.");
            }
            return $this->sendResponse($song, "Song details retrieved successfully.");

        } catch (Exception $e) {
            return $this->sendError("An error occurred: ". $e->getMessage(), [], 500);
        }
    }
    public function topSong($songId)
    {
        try {
            $song = Song::find($songId);
            if (!$song) {
                return $this->sendError('Song not found.', [], 404);
            }

            $song->is_topsong = !$song->is_topsong;
            $song->save();
            $message = $song->is_topsong
                ? 'Song marked as Top Song successfully.'
                : 'Song removed from Top Songs successfully.';
            return $this->sendResponse($song, $message);
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
