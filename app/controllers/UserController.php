<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
		return Response::json(['result' => true, 'data' => $users->toArray()], 200);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
		    		'first_name=' => Input::get('first_name'),
		    		'last_name' => Input::get('last_name'),
		        'email'     => Input::get('email'),
		        'password'  => Input::get('password'),
		        'activated' => true,
		    ));

		    // Find the group using the group id
		    $userGroup = Sentry::findGroupByName("Users");

		    // Assign the group to the user
		    $user->addGroup($userGroup);

		    return Response::json(['result' => true, 'data' => $user], 200);
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
		    $data[] = 'Group was not found.';
		}


    return Response::json(['result' => false, 'data' => $data], 400);
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
		return Response::json(['result' => true, 'data' => $user], 200);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
