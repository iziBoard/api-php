<?php

class MarkerController extends \BaseController {

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
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$page = Page::find(Input::get('id'));
    $marker = Marker::create(Input::all());
    $page->markers()->save($marker);
    return $marker;
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = Page::with('markers')->find($id);
	  //$markers = $page->markers();
	  return $page;
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
		$marker = Marker::find($id);
    $marker->update(Input::all());
    return $marker;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$marker = Marker::find($id)->delete();
		return $marker;
	}


}