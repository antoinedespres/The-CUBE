<?php

// Define the routes available in the application. The keys are the patterns,
// and are regular expressions to allow matching with the request URI. The
// values are of the form `Controller@action`, which allows for a controller to
// handle multiple actions.
return [
    // créer les routes GET et POST
    '/users' => 'Controller\Users@list',
    '/register' => 'Controller\Users@register',
    '/login' => 'Controller\Users@login',
    '/forgottenpassword' => 'Controller\Users@forgottenPassword',
    '/resetPassword' =>'Controller\Users@resetPassword',
    '/deleteAccount' => 'Controller\Users@deleteAccount',
    '/disconnect' => 'Controller\Users@disconnect',
    '/uploadFile' => 'Controller\File@upload',
    '/deleteFile' => 'Controller\File@delete',
    '/sharedFiles' => 'Controller\File@sharedList',
    '/shareFile' => 'Controller\File@Share',
    '/yourFiles' => 'Controller\File@list',
    '/search' => 'Controller\File@searchFiles',
    '/createDir' => 'Controller\File@createDirectory',
    '/edit' => 'Controller\File_Editing@showFiles',
    '/File_Editing' => 'Controller\File_Editing@fileContent',
    '/' => 'Controller\Home@get'
];
