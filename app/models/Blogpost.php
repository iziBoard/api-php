<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

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

class Blogpost extends Eloquent {

  protected $table = 'blogposts';

  protected $softDelete = true;

  protected $fillable = array('title');    


  public function blogpostable()
  {
    return $this->morphTo();
  }

  public function texts()
  {
    return $this->morphMany('Text', 'textable');
  }

  public function images()
  {
    return $this->morphMany('Photo', 'imageable');
  }

  public function categories()
  {
    return $this->morphToMany('Category', 'categoryable');
  }

  public function tags()
  {
    return $this->morphToMany('Tag', 'taggable');
  }
}