<?php

// Define the routes available in the application. The keys are the patterns,
// and are regular expressions to allow matching with the request URI. The
// values are of the form `Controller@action`, which allows for a controller to
// handle multiple actions.
return [
    '/articles/([0-9]+)' => 'Controller\Articles@get',
    '/users' => 'Controller\Users@list',
    '/register' => 'Controller\Users@register',
    '/login' => 'Controller\Users@login'
];
