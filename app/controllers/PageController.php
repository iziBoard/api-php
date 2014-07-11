<?php

class PageController extends BaseController {


	public function __construct()
	{
    $this->beforeFilter('iziAdmin', ['on' => ['post', 'put', 'path', 'delete', 'patch']]);
	}

	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$page = Page::with(['texts', 'images', 'markers', 'questions'])->get();

		if( !!$page ){
			return Response::json(['result' => true, 'data' => $page->toArray()], 200);
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
		return Response::json(['result' => false, 'data' => ['Not found']], 404);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $page = Page::create(Input::all());

    if( !!$page ){
    	$page = Page::with(['texts', 'images', 'markers', 'questions'])->where('id', $page->id)->get();
    	return Response::json(['result' => true, 'data' => $page->toArray()], 200);
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
		$page = Page::with(['texts', 'images', 'markers', 'questions'])->where('id', $id)->get();

		if( !!$page ){
			return Response::json(['result' => true, 'data' => $page->toArray()], 200);
		}
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
    $page = Page::find(Input::get('id'));
    $page->update(Input::only('title', 'heading', 'permissions', 'type'));
    
    $texts = Input::get('texts');
    foreach($texts as $text){
      $txt = Text::find($text['id'])->update($text);
    }

    return Response::json(['result' => true, 'data' => $page->toArray()], 200);
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
    return Response::json(['result' => true, 'data' => $page->toArray()], 200);
	}

}