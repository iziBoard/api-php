<?php

class UrlController extends BaseController {


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
    $urls = Url::all();
    if( !!$urls ){
      return Response::json(['result' => true, 'data' => $urls->toArray()], 200);
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
    $url = Url::create(Input::all());

    if( !!$url ){
      if( Input::has('id') ){
        $footer = Footer::find(Input::get('id'));
        $footer->urls()->save($url);
      }
      return Response::json(['result' => true, 'data' => $url->toArray()], 200);
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
    $url = Url::find($id);

    if( !!$url ){
      return Response::json(['result' => true, 'data' => $url->toArray()], 200);
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
    $url = Url::find($id);

    if( !!$url ){
      $url->update(Input::all());

      return Response::json(['result' => true, 'data' => $url->toArray()], 200);
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
    $url = Url::find($id);

    if( !!$url ){
      $url = $url->delete();
      return Response::json(['result' => true, 'data' => $url->toArray()], 200);
    }
    return Response::json(['result' => false, 'data' => ['Resounce not found']], 404);
  }


}