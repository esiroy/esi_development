<?php
/**
* Custom  Redirect Home Page if first sgemetn is public/index.php
*/
//$PROTOCOL = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTP_X_FORWARDED_PORT'] == 443) ? "https://" : "http://";

$PROTOCOL = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ) ? "https://" : "http://";

$host = $PROTOCOL. $_SERVER['HTTP_HOST'];

$uri_segments = explode('/', substr($_SERVER['REQUEST_URI'], 1), 3);

if ($uri_segments[0] == 'public' && $uri_segments[1] == 'index.php') {

    if (isset($uri_segments[2])) {        
        header("Location: $host/$uri_segments[2]"); 
    } else {    
        header("Location: $host"); 
    }
    exit();

} else if ($uri_segments[0] == 'public' ) {

    if (isset($uri_segments[1])) {        
        header("Location: $host/$uri_segments[1]"); 
    } else {    
        header("Location: $host"); 
    }
    exit();
}

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

//require __DIR__.'/../vendor/autoload.php';


require __DIR__.'/../vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

//$app = require_once __DIR__.'/../bootstrap/app.php';


$app = require_once __DIR__.'/../bootstrap/app.php';


/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
