<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBannerRequest;
use App\Models\Banner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function banner()
    {
        try {
            $banners = Banner::all();
            return $this->sendResponse($banners, 'Banners retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function createBanner(CreateBannerRequest $request)
    {
        try {
            $validated = $request->validated();
            $banner = Banner::where('description', $validated['description'])->first();
            if ($banner && $banner->thumbnail) {
                $oldThumbnailPath = str_replace('storage/', '', $banner->thumbnail); // get correct relative path
                if (Storage::disk('public')->exists($oldThumbnailPath)) {
                    Storage::disk('public')->delete($oldThumbnailPath);
                }
            }
            if ($request->hasFile('thumbnail')) {
                $image = $request->file('thumbnail');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/thumbnail', $imageName, 'public');
                $validated['thumbnail'] = 'storage/' . $path;
            }
            $banner = Banner::updateOrCreate(
                ['description' => $validated['description']],
                $validated
            );
            return $this->sendResponse($banner, 'Banner created/updated successfully');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

}
