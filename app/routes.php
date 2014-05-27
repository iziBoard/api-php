<?php

/*
 * iziBoard
 * Copyright (C) 2014  Andreas Göransson
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


/* ============ AUTH ============= */

Route::resource('auth', 'AuthController');



/* ============ PAGE ============= */

Route::get('pages', function(){
  $data = Page::all(['id', 'title', 'heading', 'permissions']);
  return Response::json(['result' => true, 'data' => $data->toArray()]);
});

Route::get('pages/{id}', function($id){
  $data = Page::with(array('texts', 'images', 'markers', 'questions'))->where('id', $id)->first();
  return Response::json(['result' => true, 'data' => $data->toArray()]);
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('pages', function(){
    $page = Page::create(Input::all());
    $data = Page::with(array('texts', 'images', 'markers'))->where('id', $page->id)->first();
    return Response::json(['result' => true, 'data' => $data->toArray()]);
  });

  Route::put('pages', function(){
    $page = Page::find(Input::get('id'));
    $page->update(Input::only('title', 'heading', 'permissions', 'type'));
    $texts = Input::get('texts');

    foreach($texts as $text){
      $txt = Text::find($text['id'])->update($text);
    }

    return Response::json(['result' => true, 'data' => $page->toArray()], 200);
  });

  Route::delete('pages/{id}', function($id){
    $page = Page::find($id);
    $page->delete();
    return $page;
  });
});





/* ============ PRODUCTS ============= */

Route::get('products', function(){
  $data = Product::all(['id', 'title', 'heading', 'permissions']);
  return Response::json(['result' => true, 'data' => $data->toArray()]);
});

Route::get('products/{id}', function($id){
  $data = Product::with(array('texts', 'images', 'markers', 'questions'))->where('id', $id)->first();
  return Response::json(['result' => true, 'data' => $data->toArray()]);
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('products', function(){
    $product = Product::create(Input::all());
    $data = Product::with(array('texts', 'images', 'markers'))->where('id', $product->id)->first();
    return Response::json(['result' => true, 'data' => $data->toArray()]);
  });

  Route::put('products', function(){
    $product = Product::find(Input::get('id'));
    $product->update(Input::only('title', 'heading', 'permissions', 'type'));
    $texts = Input::get('texts');

    foreach($texts as $text){
      $txt = Text::find($text['id'])->update($text);
    }

    return Response::json(['result' => true, 'data' => $product->toArray()], 200);
  });

  Route::delete('products/{id}', function($id){
    $product = Product::find($id);
    $product->delete();
    return $product;
  });
});






/* ============ TEXT (able) ============= */

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('texts', function(){
    $text = Text::create(Input::only('description'));

    if( Input::has('id') && Input::get('id') != 'undefined' ){
      $containerId = Input::get('id');
      $containerType = Input::get('type');
      $container = $containerType::find($containerId);
      $container->texts()->save($text);
    }

    return Response::json(['result' => true, 'data' => $text->toArray()], 200);
  });

  Route::delete('texts/{id}', function($id){
    $text = Text::find($id);
    $text->delete();
    return Response::json(['result' => true, 'data' => $text->toArray()], 200);
  });

  Route::put('texts', function(){
    $text = Text::find(Input::get('id'));
    $text->update(Input::all());
    return Response::json(['result' => true, 'data' => $text->toArray()], 200);
  });
});




/* ============ IMAGE (able) ============= */

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('imageable', function(){
    $path = public_path();

    $photo = null;

    if( Input::has('updatefile') ){

      $photo = Photo::find(Input::get('updatefile'));

      $file = Input::file('file');

      $originalName = $file->getClientOriginalName();

      $date = new DateTime();
      $name = $date->getTimestamp();
      $ext = $file->getClientOriginalExtension();

      $filename = $name . '.' . $ext;

      $image = Image::make(Input::file('file')->getRealPath());

      File::exists($path.'/uploads/') or File::makeDirectory($path.'/uploads/');

      $image->save($path.'/uploads/'.$name.'.'.$ext);

      $image->resize(null, 200, true)->crop(100, 100);


      $image->save($path.'/uploads/'.$name.'_thumb.'.$ext);

      $thumbnail = $name.'_thumb.'.$ext;

      $data = array(
        'originalname' => $originalName,
        'name' => $name,
        'ext' => $ext,
        'filename' => asset('uploads/'.$filename),
        'thumbnail' => asset('uploads/'.$thumbnail)
      );

      $photo->update($data);

    }else{

      if( count(Input::file('file')) > 0 ){
        $file = Input::file('file');

        $originalName = $file->getClientOriginalName();

        $date = new DateTime();
        $name = $date->getTimestamp();
        $ext = $file->getClientOriginalExtension();

        $filename = $name . '.' . $ext;
        
        try{
          $image = Image::make(Input::file('file')->getRealPath());

          File::exists($path.'/uploads/') or File::makeDirectory($path.'/uploads/');

          $image->save($path.'/uploads/'.$name.'.'.$ext);

          $image->resize(null, 200, true)->crop(100, 100);

          $image->save($path.'/uploads/'.$name.'_thumb.'.$ext);

          $thumbnail = $name.'_thumb.'.$ext;

          $data = array(
            'originalname' => $originalName,
            'name' => $name,
            'ext' => $ext,
            'filename' => asset('uploads/'.$filename),
            'thumbnail' => asset('uploads/'.$thumbnail)
          );
        }catch(Intervention\Image\Exception\InvalidImageTypeException $e){
          return "Wrong file type!";
        }
      } else {
        $data = Input::all();
      }

      $photo = Photo::create($data);

      if( Input::has('id') && Input::get('id') != 'undefined' ){
        $containerId = Input::get('id');
        $containerType = Input::get('type');
        $container = $containerType::find($containerId);
        $container->images()->save($photo);
      }
    }

    return $photo;
  });

  Route::put('imageable', function () {
    return "PUT DOES NOT WORK!";
  });
});



/* ============ NEWS ============= */

Route::get('news', function(){
  $news = News::with('categories', 'images')->orderBy('created_at', 'DESC')->get();
  return Response::json(['result' => true, 'data' => $news->toArray()], 200);
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('news', function(){

    $data = Input::only('title', 'body');
    $news = News::create($data);

    if( Input::has('images') && count(Input::get('images')) > 0 ){
      $photo = Photo::find(Input::get('images')[0]['id']);
      $news->images()->save($photo);    
    }

    $cat = Input::get('tags');
    $category = Category::find($cat['id']);
    $news->categories()->attach($category);

    $ret = News::with(array('images', 'categories'))->where('id', $news->id)->get()->first();

    return Response::json(['result' => true, 'data' => $ret->toArray()], 200);
  });

  Route::delete('news/{id}', function($id){
    $news = News::find($id);
    $news->delete();

    return Response::json(['result' => true, 'data' => $news->toArray()], 200);
  });

  Route::put('news', function(){
    $news = News::find(Input::get('id'));
    $news->update(Input::all());

    return Response::json(['result' => true, 'data' => $news->toArray()], 200);
  });
});




/* ============ MARKERS ============= */

Route::get('marker/{id}', function($id){
  $page = Page::with('markers')->find($id);
  //$markers = $page->markers();
  return $page;
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('markers', function(){

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

    if( Input::has('id') && Input::get('id') != 'undefined' ){
      $containerId = Input::get('id');
      $containerType = Input::get('type');
      $container = $containerType::find($containerId);
    }

    foreach ($csv as $row) {
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
          // No exception will be thrown here
          //echo $e->getMessage();
      }

      $marker = Marker::create($data);
      $container->markers()->save($marker);
    }
  });

  Route::post('marker', function(){
    $page = Page::find(Input::get('id'));
    $marker = Marker::create(Input::all());
    $page->markers()->save($marker);
    return $marker;
  });

  Route::put('marker', function(){
    $marker = Marker::find(Input::get('id'));
    $marker->update(Input::all());
  });

  Route::delete('marker/{id}', function($id){
    $marker = Marker::find($id)->delete();
  });
});



/* ============ CATEGORIES ============= */

Route::get('categories', function(){
  return Category::all();
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('categories', function(){
    $category = Category::create(Input::all());
    return $category;
  });
});



/* ============ POSTS ============= */

Route::get('posts', function(){
  $posts = Blogpost::all();
  return Response::json(['result' => true, 'data' => $posts->toArray()], 200);
});

Route::get('posts/{id}', function($id){
  $post = Blogpost::with(['images', 'texts'])->where('id', $id)->first();
  return Response::json(['result' => true, 'data' => $post->toArray()], 200);
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('posts', function(){
    $blogpost = Blogpost::create(Input::all());
    return Response::json(['result' => true, 'data' => $blogpost->toArray()], 200);
  });

  Route::put('posts', function(){
    $blogpost = Blogpost::find(Input::get('id'));
    $blogpost->update(Input::all());

    $texts = Input::get('texts');
    foreach($texts as $text){
      $txt = Text::find($text['id'])->update($text);
    }

    return Response::json(['result' => true, 'data' => $blogpost->toArray()], 200);
  });
});




/* ============ USERS ============= */
Route::resource('users', 'UserController');
/*
Route::post('users/login', function(){

  $user = null;
  $messages = [];

  try {
      $user = \Sentry::authenticate(Input::only(['email', 'password']), false);
      $token = hash('sha256', Str::random(10), false);
      $user->api_token = $token;
      $user->save();

      return Response::json([
        'token' => $token, 
        'user' => $user->toArray(),
        'permissions' => $user->getMergedPermissions()
      ], 202);
  } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
      $messages[] = 'Login field is required.';
  } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
      $messages[] = 'Password field is required.';
  } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
      $messages[] = 'Wrong password, try again.';
  } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
      $messages[] = 'User was not found.';
  } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
      $messages[] = 'User is not activated.';
  } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
      $messages[] = 'User is suspended.';
  } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
      $messages[] = 'User is banned.';
  }


  return Response::json([
    'type' => 'danger',
    'messages' => $messages
  ], 401);

});


Route::post('users/register', function(){
  return "Register";
});

Route::group(array('before' => 'iziAuth'), function()
{
  Route::post('users/logout', function(){
    \Sentry::logout();
    
    return Response::json([
      'flash' => 'you have been disconnected'],
      200
    );
  });

  Route::put('users', function(){
    return "Update";
  });
});

*/


/* ============ FOOTER ============= */

Route::get('footer/{id}', function($id){
  $footer = Footer::find($id);
  return $footer;
});

Route::get('footers', function(){
  $footers = Footer::with(array('texts', 'urls'))->get();
  return $footers;
});

Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('footer', function(){
    $footer = Footer::create(Input::only('type', 'title'));
    return $footer;
  });

  Route::put('footer', function(){
    $footer = Footer::find(Input::get('id'));

    $texts = Input::get('texts');
    foreach($texts as $text){
      $txt = Text::find($text['id'])->update($text);
    }

    $footer->update(Input::only('title', 'type'));

    return $footer;
  });

  Route::delete('footer/{id}', function($id){
    $footer = Footer::find($id);
    $footer->delete();
    return $footer;
  });
});




/* ============ URL ICONS ============= */
Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('url', function(){
    $url = Url::create(Input::only('title', 'url'));
    $footer = Footer::find(Input::get('id'));
    $footer->urls()->save($url);
    return $url;
  });

  Route::delete('url/{id}', function($id){
    $url = Url::find($id);
    $url->delete();
    return $url;
  });
});


Route::group(array('before' => 'iziAuth|iziAdmin'), function()
{
  Route::post('questions', function(){
    $question = Question::create(Input::only('title', 'body'));

    if( Input::has('id') && Input::get('id') != 'undefined' ){
      $containerId = Input::get('id');
      $containerType = Input::get('type');
      $container = $containerType::find($containerId);
      $container->questions()->save($question);
    }

    return Response::json(['result' => true, 'data' => $question->toArray()], 200);
  });

  Route::put('questions', function(){
    $question = Question::find(Input::get('id'));
    $question->update(Input::all());
    return $question;
  });

  Route::delete('questions/{id}', function($id){
    $question = Question::find($id);
    $question->delete();
    return $question;
  });
});

Route::post('email', function () {

  Mail::send('board::mails.contactform', Input::all(), function($message)
  {
      $message->to(Config::get('board::app.contact-email'), Input::get('name'))->subject('Contact form message');
  });

});

/* ============ ROOT ============= */

Route::get('/', function()
{
  return View::make('hello');
});

Route::any('{all}', function($uri)
{
  return Redirect::to('/');
})->where('all', '.*');