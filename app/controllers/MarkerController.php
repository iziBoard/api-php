<?php

class MarkerController extends BaseController {


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
		$markers = Marker::all();
		if( !!$markers ){
			return Response::json(['result' => true, 'data' => $markers->toArray()], 200);
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
		if( Input::has('id') && Input::get('id') != 'undefined' ){
      $containerId = Input::get('id');
      $containerType = Input::get('type');
      $container = $containerType::find($containerId);
    }

		if( Input::hasFile('file') ){
			$path = public_path() . '/uploads/';

	    $file = Input::file('file');
	    
	    $originalName = $file->getClientOriginalName();

	    $date = new DateTime();
	    $name = $date->getTimestamp();
	    $ext = $file->getClientOriginalExtension();

	    $filename = $name . '.' . $ext;

	    $file->move($path, $filename);

	    $csv = new League\Csv\Reader($path . $filename);
	    $csv->setDelimiter(';');
	    $csv->setEncoding("iso-8859-15");

	    foreach( $csv as $row ){
	      $data = [
	        'title' => $row[0],
	        'street' => $row[1],
	        'zip' => $row[2],
	        'city' => $row[3],
	        'country' => 'Sweden',
	      ];

	      try {
	          $geocode = Geocoder::geocode($data['street'] . ' ' . $data['zip'] . ' ' . $data['city']);
	          $data['latitude'] = $geocode['latitude'];
	          $data['longitude'] = $geocode['longitude'];
	      } catch (\Exception $e) {
	      }

	      $marker = Marker::create($data);

	      if( !!$container )
	      	$container->markers()->save($marker);
	    }
		}else{
			$marker = Marker::create(Input::all());

			if( !!$container )
				$container->markers()->save($marker);
		}

		

    return Response::json(['result' => true, 'data' => $marker->toArray()], 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//$page = Page::with('markers')->find($id);
	  $marker = Marker::find($id);

	  if( !!$marker ){
	  	return Response::json(['result' => true, 'data' => $marker->toArray()], 200);
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
		$marker = Marker::find($id);
		if( !!$marker ){
			$marker->update(Input::all());
			return Response::json(['result' => true, 'data' => $marker->toArray()], 200);	
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
		$marker = Marker::find($id);
		if( !!$marker ){
			$marker->delete();
			return Response::json(['result' => true, 'data' => $marker->toArray()], 200);
		}
		return Response::json(['result' => false, 'data' => ['Resource not found']], 404);
	}


}