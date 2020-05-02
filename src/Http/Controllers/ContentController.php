<?php

namespace VinnyGambiny\LightroomSync\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ContentController extends BaseController
{
    public function show(Request $request, $content_id)
    {
        return config('media-library.media_model')::findOrFail($content_id)
            ->toArray();
    }

    public function store(Request $request)
    {
        if (!$request->input('albumId')) {
            return [
                'error' => 'no album'
            ];
        }

        $file = $request->file('file');
        $path = config('ligthroom-sync.chunks_folder') . '/' . $file->getClientOriginalName();

        Storage::append($path, $file->get());

        if ($request->input('chunk') == $request->input('chunks') - 1) {
            $album = config('lightroom-sync.album_model')::find($request->input('albumId'));

            if (!$album) {
                Storage::disk(config('lightroom-sync.chunks_disk'))->delete($path);

                return [
                    'error' => 'album not found'
                ];
            }

            return $album->addMedia(Storage::disk(config('lightroom-sync.chunks_disk'))->path($path))
                ->usingName($request->input('name'))
                ->toMediaCollection(config('lightroom-sync.media_collection'))
                ->toArray();
        }

        return [];
    }

    public function update(Request $request, $content_id)
    {
        $content = config('media-library.media_model')::findOrFail($content_id);
        $file = $request->file('file');
        $path = config('ligthroom-sync.chunks_folder') . '/' . $file->getClientOriginalName();

        Storage::disk(config('lightroom-sync.chunks_disk'))->append($path, $file->get());

        if ($request->input('chunk') == $request->input('chunks') - 1) {
            Storage::disk($content->disk)->delete($content->getPath());
            Storage::disk($content->disk)->put($content->getPath(), Storage::disk(config('lightroom-sync.chunks_disk'))->get($path));
            Storage::disk(config('lightroom-sync.chunks_disk'))->delete($path);
            Artisan::call('media-library:regenerate', [
                '--ids' => $content->id,
            ]);
        }

        return $content->toArray();
    }

    public function destroy(Request $request, $content_id)
    {
        $content = config('media-library.media_model')::findOrFail($content_id);
        $content->delete();

        return [
            'success' => true
        ];
    }
}
