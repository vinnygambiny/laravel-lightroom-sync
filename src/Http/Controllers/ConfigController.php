<?php

namespace VinnyGambiny\LightroomSync\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ConfigController extends BaseController
{
    public function index(Request $request)
    {
        $max_upload = min(ini_get('post_max_size'), ini_get('upload_max_filesize'));
        $max_upload = str_replace('M', '', $max_upload);
        $max_upload = $max_upload * 1024;

        return [
            'upload_limit' => $max_upload
        ];
    }
}
