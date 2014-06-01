<?php

class UserController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = Sentry::findAllUsers();

		$data = [];
		foreach( $users as $user ){
			$usr = $user->toArray();
			$usr['permissions'] = $user->getMergedPermissions();
			$data[] = $usr;
		}

		return Response::json(['result' => true, 'data' => $data], 200);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Response::json(['result' => false, 'data' => ['Not found']], 404);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = array();

		try
		{
		    // Create the user
		    $user = Sentry::createUser(array(
		    		'first_name' => Input::get('first_name'),
		    		'last_name' => Input::get('last_name'),
		        'email'     => Input::get('email'),
		        'password'  => Input::get('password'),
		        'activated' => true,
		    ));

		    // Find the group using the group id
		    $userGroup = Sentry::findGroupByName("Users");

		    // Assign the group to the user
		    $user->addGroup($userGroup);

		    $data = $user->toArray();
		    $data['permissions'] = $user->getMergedPermissions();

		    return Response::json(['result' => true, 'data' => $data], 200);
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    $data[] = 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		    $data[] = 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    $data[] = 'User with this login already exists.';
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
		    return Response::json(['result' => false, 'data'  => ['Group was not found.']], 404);
		}


    return Response::json(['result' => false, 'data' => $data->toArray()], 400);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);

		if( !!$user ){
			return Response::json(['result' => true, 'data' => $user->toArray()], 200);	
		}
		return Response::json(['result' => false, 'data' => ['Resource not found']], 404);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return Response::json(['result' => false, 'data' => ['Not found']], 404);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$messages = [];
		
		try
		{
	    $user = Sentry::findUserById($id);

	    if( Input::has('email') ){
	    	$user->email = Input::get('email');
	    }

	    if( Input::has('first_name') ){
	    	$user->first_name = Input::get('first_name');
	    }

	    if( Input::has('last_name') ){
	    	$user->last_name = Input::get('last_name');
	    }

	    if( Input::has('password') ){
	    	$user->password = Input::get('passwoed');
	    }

	    // Update the user
	    if ( $user->save() ){
	   		return Response::json(['result' => true, 'data' => $user->toArray()], 200);
	    } else {
	    	return Response::json(['result' => false, 'data' => ['User was not updated']], 401);
	    }
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
	    $messages[] = 'User with this login already exists.';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
	    return Response::json(['result' => false, 'data' => ['User was not found.']], 404);
		}

		return Response::json(['result' => false, 'data' => $messages], 400);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
	    // Find the user using the user id
	    $user = Sentry::findUserById($id);

	    // Delete the user
	    $user->delete();

			return Response::json(['result' => true, 'data' => $user->toArray()], 200);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
	    $messages[] = 'User was not found.';
		}

		return Response::json(['result' => false, 'data' => $messages], 400);
	}


}
