<?php

class CategoryController extends BaseController {


	public function __construct()
	{
    $this->beforeFilter('iziAuth|iziAdmin', ['on' => ['post', 'put', 'path', 'delete', 'patch']]);
	}
	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$categories = Category::all();
		if( !!$categories ){
			return Response::json(['result' => true, 'data' => $categories->toArray()], 200);
		}
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
		$category = Category::create(Input::all());

		if( !!$category ){
			return Response::json(['result' => true, 'data' => $category->toArray()], 200);
		}
    return Response::json(['result' => false, 'data' => ['Resource not created']], 400);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$category = Category::find($id);

		if( !!$category ){
			return Response::json(['result' => true, 'data' => $category->toArray()], 200);
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
		$category = Category::find($id);

		if( !!$category ){
			$category->update(Input::all());
			return Response::json(['result' => true, 'data' => $category->toArray()], 200);
		}
		return Response::json(['result' => false, 'data' => ['Resource not found']], 404);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$category = Category::find($id);

		if( !!$category ){
			$category = $category->delete();
			return Response::json(['result' => true, 'data' => $category->toArray()], 200);
		}
		return Response::json(['result' => false, 'data' => ['Resounce not found']], 404);
	}


}