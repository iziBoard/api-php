<?php

class FooterController extends BaseController {


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
    $footers = Footer::all();
    if( !!$footers ){
      return Response::json(['result' => true, 'data' => $footers->toArray()], 200);
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
    $footer = Footer::create(Input::all());

    if( !!$footer ){
      return Response::json(['result' => true, 'data' => $footer->toArray()], 200);
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
    $footer = Footer::find($id);

    if( !!$footer ){
      return Response::json(['result' => true, 'data' => $footer->toArray()], 200);
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
    $footer = Footer::find($id);

    if( !!$footer ){
      $footer->update(Input::all());

      $texts = Input::get('texts');
      foreach($texts as $text){
        $txt = Text::find($text['id'])->update($text);
      }
      return Response::json(['result' => true, 'data' => $footer->toArray()], 200);
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
    $footer = Footer::find($id);

    if( !!$footer ){
      $footer = $footer->delete();
      return Response::json(['result' => true, 'data' => $footer->toArray()], 200);
    }
    return Response::json(['result' => false, 'data' => ['Resounce not found']], 404);
  }


}