<?php

class TextController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('iziAuth|iziAdmin', ['on' => ['post', 'put', 'path', 'delete']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$text = Text::create(Input::only('description'));

    if( Input::has('id') && Input::get('id') != 'undefined' ){
      $containerId = Input::get('id');
      $containerType = Input::get('type');
      $container = $containerType::find($containerId);
      $container->texts()->save($text);
    }

    return $text;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
		$text = Text::find(Input::get('id'));
    $text->update(Input::all());
    return $text;
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
    $text->delete();
    return $text;
	}


}