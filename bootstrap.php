<?php

session_start();

// Configure the autoloader. The autoloader is triggered by a class being used
// while not existing, just before throwing an error.
spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    require($class_name.'.php');
});

// Include the routes file.
$routes = require('routes.php');

//
$config = require('config.php');

// Connect to the database.
$db = new \PDO("sqlite:{$config['database']['path']}");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Try to match the routes to the request URI. If a match is found, a boolean
// flag is set so it's easy to determine if there was a match. As PHP variables
// aren't block-scoped, the $action and $matches variables are available outside
// the loop to provide the parameters to the function afterwards.
$matched = false;
if(isset($_SESSION['UserID']))
    foreach($routes['loggedIn'] as $pattern => $action) {
        if (preg_match("#$pattern#", $_SERVER['REQUEST_URI'], $matches) === 1) {
            $matched = true;
            break;
        }
    }
else
    foreach($routes['loggedOut'] as $pattern => $action) {
        if (preg_match("#$pattern#", $_SERVER['REQUEST_URI'], $matches) === 1) {
            $matched = true;
            break;
        }
    }

// If nothing matched, return a 404.
if (!$matched) {
    http_response_code(404);
    echo '404';
    exit;
}

// Define the render function used by the controller to define the view to
// display.
function render($view, $data = []) {
    require("view/$view.php");
}

// Split the route's action into class and method, initialize the controller
// from the class, then call the given method using the matched parameters.
list($class, $method) = explode('@', $action);
$controller = new $class();
call_user_func_array([$controller, $method], []);
//call_user_func_array([$controller, $method], array_slice($matches, 1));
