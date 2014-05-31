<?php

class NewsController extends BaseController {


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
		$news = News::with('categories', 'images')->orderBy('created_at', 'DESC')->get();

		if( !!$news ){
			return Response::json(['result' => false, 'data' => $news->toArray()], 200);
		}

		return Response::json(['result' => false, 'data' => ['Resource not found']], 404);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Response::json(['result' => false, 'data' => ['Not found']], 200);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $news = News::create(Input::only('title', 'body'));

    if( Input::has('images') && count(Input::get('images')) > 0 ){
      $photo = Photo::find(Input::get('images')[0]['id']);
      $news->images()->save($photo);    
    }

    $cat = Input::get('tags');
    $category = Category::find($cat['id']);
    $news->categories()->attach($category);

    if( !!$news ){
    	$news = News::with(array('images', 'categories'))->where('id', $news->id)->get();
    	return Response::json(['result' => true, 'data' => $news->toArray()], 200);
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
		$news = News::find($id);

		if( !!$news ){
			return Response::json(['result' => true, 'data' => $news->toArray()], 200);
		}

		return Response::json(['result' => false, 'data' => ['Resounrce not found']], 404);
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
		$news = News::find(Input::get('id'));
    
		if( !!$news ){
			$news = $news->update(Input::all());
			return 	Response::json(['result' => true, 'data' => $news->toArray()], 200);
		}
    
    return Response::json(['result' => false, 'data' => ['Resounrce not found']], 404);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$news = News::find($id);

		if( !!$news ){
			$news = $news->delete();
			return Response::json(['result' => true, 'data' => $news->toArray()], 200);
		}

		return Response::json(['result' => false, 'data' => ['Resounrce not found']], 404);
	}


}