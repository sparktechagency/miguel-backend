<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function artist(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $artists = Artist::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->paginate($perPage);

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

    public function updateArtist(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:artists,id',
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'gender' => 'in:male,female,other',
                'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $artist = Artist::find($request->id);

            $artist->fill($request->only(['name', 'description', 'gender']));

            if ($request->hasFile('profile')) {
                $image = $request->file('profile');
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

            // Optionally delete profile image if not default
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
