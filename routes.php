<?php

// Define the routes available in the application. The keys are the patterns,
// and are regular expressions to allow matching with the request URI. The
// values are of the form `Controller@action`, which allows for a controller to
// handle multiple actions.
return [
    '/users' => 'Controller\Users@list',
    '/register' => 'Controller\Users@register',
    '/login' => 'Controller\Users@login',
    '/upload' => 'Controller\File@upload',
    '/' => 'Controller\Home@get'
];
