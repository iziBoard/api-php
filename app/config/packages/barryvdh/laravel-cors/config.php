<?php

return array(

    /*
     |--------------------------------------------------------------------------
     | Laravel CORS Defaults
     |--------------------------------------------------------------------------
     |
     | The defaults are the default values applied to all the paths that match,
     | unless overridden in a specific URL configuration.
     | If you want them to apply to everything, you must define a path with ^/.
     |
     | allow_origin and allow_headers can be set to * to accept any value,
     | the allowed methods however have to be explicitly listed.
     |
     */
    'defaults' => array(
        'allow_credentials' => false,
        'allow_origin' => array(),
        'allow_headers' => array(),
        'allow_methods' => array(),
        'expose_headers' => array(),
        'max_age' => 0,
    ),

    'paths' => array(
        '^/' => array(
            'allow_origin' => array('*'),
            'allow_headers' => array('Content-Type', 'Accept', 'Origin', 'X-Requested-With', 'Last-Modified', 'X-Auth-Token'),
            'allow_methods' => array('POST', 'PUT', 'GET', 'DELETE', 'PATCH'),
            'max_age' => 3600,
        ),
    ),

);
/*

App::before(function($request)
{
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
        exit;
    }
});
*/