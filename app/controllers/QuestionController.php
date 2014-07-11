<?php

class QuestionController extends BaseController {


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
    $questions = Question::all();
    if( !!$questions ){
      return Response::json(['result' => true, 'data' => $questions->toArray()], 200);
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
    $question = Question::create(Input::only('title', 'body'));

    if( !!$question ){
      if( Input::has('id') && Input::get('id') != 'undefined' ){
        $containerId = Input::get('id');
        $containerType = Input::get('type');
        $container = $containerType::find($containerId);
        $container->questions()->save($question);
      }
      return Response::json(['result' => true, 'data' => $question->toArray()], 200);
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
    $question = Question::find($id);

    if( !!$question ){
      return Response::json(['result' => true, 'data' => $question->toArray()], 200);
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
    $question = Question::find($id);

    if( !!$question ){
      $question->update(Input::all());

      return Response::json(['result' => true, 'data' => $question->toArray()], 200);
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
    $question = Question::find($id);

    if( !!$question ){
      $question = $question->delete();
      return Response::json(['result' => true, 'data' => $question->toArray()], 200);
    }
    return Response::json(['result' => false, 'data' => ['Resounce not found']], 404);
  }


}