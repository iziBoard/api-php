<?php

class BlogpostController extends BaseController {


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
		$posts = Blogpost::all();

		if( !!$posts ){
			return Response::json(['result' => true, 'data' => $posts->toArray()], 200);
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
		$post = Blogpost::create(Input::all());

		if( !!$post ){
			return Response::json(['result' => true, 'data' => $post->toArray()], 200);
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
		$post = Blogpost::with(['images', 'texts'])->where('id', $id)->get();

		if( !!$post ){
			return Response::json(['result' => true, 'data' => $post->toArray()], 200);
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
		$post = Blogpost::find($id);

		if( !!$post ){
			$post = $post->update(Input::all());

			$texts = Input::get('texts');
	    foreach($texts as $text){
	      $txt = Text::find($text['id'])->update($text);
	    }

			return Response::json(['result' => true, 'data' => $post->toArray()], 200);
		}
		return Response::json(['result' => false, 'data' =>  ['Resource not found']], 404);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$post = Blogpost::find($id);

		if( !!$post ){
			$post = $post->delete();
			return Response::json(['result' => true, 'data' => $post->toArray()], 200);
		}
		return Response::json(['result' => false, 'data' => ['Resource not found']], 404);
	}


}