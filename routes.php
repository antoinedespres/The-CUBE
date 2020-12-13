<?php

// Define the routes available in the application. The keys are the patterns,
// and are regular expressions to allow matching with the request URI. The
// values are of the form `Controller@action`, which allows for a controller to
// handle multiple actions.
return [
    'loggedOut' => [
        '/register' => 'Controller\Users@register',
        '/login' => 'Controller\Users@login',
        '/forgottenPassword' => 'Controller\Users@forgottenPassword',
        '/resetPassword' => 'Controller\Users@resetPassword',
        '/' => 'Controller\Home@get'
    ],
    'loggedIn' => [
        '/deleteAccount' => 'Controller\Users@deleteAccount',
        '/changePassword' => 'Controller\Users@changePassword',
        '/disconnect' => 'Controller\Users@disconnect',
        '/uploadFile' => 'Controller\File@upload',
        '/deleteFile' => 'Controller\File@delete',
        '/shareFile' => 'Controller\File@Share',
        '/yourFiles' => 'Controller\File@getFiles',
        '/search' => 'Controller\File@searchFiles',
        '/edit' => 'Controller\File_Editing@showFiles',
        '/createDir' => 'Controller\File@createDirectory',
        '/File_Editing' => 'Controller\File_Editing@fileContent',
        '/' => 'Controller\Home@get'
    ]
];