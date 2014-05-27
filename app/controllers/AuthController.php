<?php

class AuthController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json(['result' => false, 'data' => [Lang::get('responses.bad_request')]], 404);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Response::json(['result' => false, 'data' => [Lang::get('responses.bad_request')]], 404);
	}


	/**
	 * Store a newly created resource in storage.
	 * 
	 * LOGIN !!!
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = array();
    try
    {
      $user = Sentry::authenticate(Input::only(['email', 'password']), false);

      $data = $user->toArray();
      $data['persistCode'] = $user->persist_code; // TODO, is this SECURE?
      $data['permissions'] = $user->getMergedPermissions();

      return Response::json(['result' => true, 'data' => $data], 202);
    }
    catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
    {
      $data[] = 'Login field is required.';
    }
    catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
    {
      $data[] = 'Password field is required.';
    }
    catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
    {
      $data[] = 'Wrong password, try again.';
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
      $data[] = 'User was not found.';
    }
    catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
    {
      $data[] = 'User is not activated.';
    }

    // The following is only required if throttle is enabled
    catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
    {
      $data[] = 'User is suspended.';
    }
    catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
    {
      $data[] = 'User is banned.';
    }

    return Response::json(['result' => false, 'data' => $data], 401);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Response::json(['result' => false, 'data' => [Lang::get('responses.bad_request')]], 404);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return Response::json(['result' => false, 'data' => [Lang::get('responses.bad_request')]], 404);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * ACTIVATE !!!
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$data = array();

    try
    {
      // Find the user using the user activation code
      $user = Sentry::findUserByActivationCode(Input::get('code'));

      if( !$user )
      {
        // Unknown activation code
        $data[] = 'Unknown activation code';
      } else {
        // Attempt to activate the user
        if ($user->attemptActivation(Input::get('code')))
        {
          // User activation passed
          $data[] = 'User activated';
          return Response::json(array('result' => true, 'data' => $data));
        }
        else
        {
          // User activation failed
          $data[] = 'User not activated';
        }
      }
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
      $data[] = 'User was not found.';
    }
    catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
    {
      $data[] = 'User is already activated.';
    }


    return Response::json(array('result' => false, 'data' => $data));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * LOGOUT !!!
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
    $userModel = Sentry::getUserProvider()->createModel();
    $user = $userModel->where('id', $id)->where('persist_code', Input::get('token'))->first();

    if( !$user ){
      return Response::json(['result' => false, 'data' => ['Failed to logged out.']], 400);
    }else{
      $user->persist_code = null;
      $user->save();
      return Response::json(['result' => true, 'data' => ['User logged out.']], 200);
    }
	}


}