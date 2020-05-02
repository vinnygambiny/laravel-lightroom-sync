<?php

return [

    'middleware' => 'auth:api',

    'album_model' => '/App/Album',

    'chunks_folder' => storage_path('chunks'),
    'chunks_disk' => 'local',

    'media_collection' => 'lightroom',

];
