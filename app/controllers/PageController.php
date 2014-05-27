<?php

class PageController extends \BaseController {

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
		return Page::with(array('texts', 'images', 'markers', 'blogposts', 'blogposts.images', 'blogposts.texts', 'accordions'))->get();
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
    $page = Page::create(Input::all());
    return Page::with(array('texts', 'images', 'markers'))->where('id', $page->id)->first();
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
    $page = Page::find(Input::get('id'))->update(Input::only('title', 'body'));
    $texts = Input::get('texts');

    foreach($texts as $text){
      $txt = Text::find($text['id'])->update($text);
    }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
    $page = Page::find($id);
    $page->delete();
    return $page;
	}


}