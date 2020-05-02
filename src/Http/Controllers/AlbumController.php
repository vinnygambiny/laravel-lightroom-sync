<?php

namespace VinnyGambiny\LightroomSync\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class AlbumController extends BaseController
{
    public function show(Request $request, $album_id)
    {
        return config('lightroom-sync.album_model')::findOrFail($album_id);
    }

    public function store(Request $request)
    {
        return config('lightroom-sync.album_model')::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'visible' => $request->input('visibility') == 'public',
            'published_at' => now(),
        ]);
    }

    public function update(Request $request, $album_id)
    {
        $album = config('lightroom-sync.album_model')::findOrFail($album_id);
        $album->title = $request->input('title');
        $album->save();

        return $album;
    }

    public function destroy(Request $request, $album_id)
    {
        $album = config('lightroom-sync.album_model')::findOrFail($album_id);
        $album->delete();

        return [
            'success' => true
        ];
    }
}
