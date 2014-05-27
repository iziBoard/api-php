<?php

class NewsController extends \BaseController {

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
		return News::with('categories', 'images')->orderBy('created_at', 'DESC')->get();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data = Input::only('title', 'body');
    $news = News::create($data);

    if( Input::has('images') && count(Input::get('images')) > 0 ){
      $photo = Photo::find(Input::get('images')[0]['id']);
      $news->images()->save($photo);    
    }

    $cat = Input::get('tags');
    $category = Category::find($cat['id']);
    $news->categories()->attach($category);

    return News::with(array('images', 'categories'))->where('id', $news->id)->get()->first();
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
		$news = News::find(Input::get('id'));
    $news->update(Input::all());
    return $news;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$news = News::find($id)->delete();
		return $news;
	}


}