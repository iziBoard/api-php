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


Route::group(['prefix' => 'v1'], function (){

  Route::resource('auth', 'AuthController');
  Route::resource('users', 'UserController');
  Route::resource('pages', 'PageController');

  Route::resource('products', 'ProductController');

  Route::resource('texts', 'TextController');
  Route::resource('markers', 'MarkerController');
  Route::resource('categories', 'CategoryController');
  Route::resource('questions', 'QuestionController');
  Route::resource('photos', 'PhotoController');
  Route::resource('urls', 'UrlController');

  Route::resource('posts', 'BlogpostController');
  Route::resource('news', 'NewsController');
  Route::resource('footers', 'FooterController');

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