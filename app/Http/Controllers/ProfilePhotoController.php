<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $user = $request->user();
        $previous = $user->profile_photo_path;
        $path = $request->file('photo')->storePublicly('profile-photos', [
            'disk' => config('jetstream.profile_photo_disk', 'public'),
        ]);

        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        if ($previous) {
            Storage::disk(config('jetstream.profile_photo_disk', 'public'))->delete($previous);
        }

        return response()->json([
            'path' => $path,
            'url' => $user->fresh()->profile_photo_url,
        ]);
    }
}
