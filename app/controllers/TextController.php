<?php

class TextController extends BaseController {


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
		$texts = Text::all();

		return Response::json(['result' => true, 'data' => $texts->toArray()], 200);
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
		$text = Text::create(Input::only('description'));

    if( Input::has('id') && Input::get('id') != 'undefined' ){
      $containerId = Input::get('id');
      $containerType = Input::get('type');
      $container = $containerType::find($containerId);
      $container->texts()->save($text);
    }

    return Response::json(['result' => true, 'data' => $text->toArray()], 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$text = Text::find($id);
		if( !!$text ){
			return Response::json(['result' => true, 'data' => $text->toArray()], 200);
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
		$text = Text::find(Input::get('id'));

		if( !!$text ){
			$text->update(Input::all());
			return Response::json(['result' => true, 'data' => $text->toArray()], 200);
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
		$text = Text::find($id);

		if( !!$text ){
			$text->delete();
			return Response::json(['result' => true, 'data' => $text->toArray()], 200);	
		}
    
    return Response::json(['result' => false, 'data' => ['Resource not found']], 404);
	}


}