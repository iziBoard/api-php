<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('iziAuth', function($route, $request){

  $payload = $request->header('X-Auth-Token');

  $userModel = Sentry::getUserProvider()->createModel();

  $user = $userModel->where('persist_code', $payload)->first();

  if(!$user || $payload == null) {
    
    $response = Response::json([
      'error' => true,
      'message' => 'Not authenticated',
      'code' => 401
      ], 401
    );

    $response->header('Content-Type', 'application/json');
    
    return $response;
  }

});

Route::filter('iziAdmin', function($route, $request){

  $payload = $request->header('X-Auth-Token');

  $userModel = Sentry::getUserProvider()->createModel();

  $user = $userModel->where('persist_code', $payload)->first();

  if(!$user || $payload == null) {
    
    $response = Response::json([
      'error' => true,
      'message' => 'Not authenticated',
      'code' => 401
      ], 401
    );

    $response->header('Content-Type', 'application/json');
    
    return $response;
  }

  if( !$user->hasAccess('admin') ) {
    
    $response = Response::json([
      'error' => true,
      'message' => 'Not authorized',
      'code' => 401
      ], 401
    );

    $response->header('Content-Type', 'application/json');
    
    return $response;
  }

});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
