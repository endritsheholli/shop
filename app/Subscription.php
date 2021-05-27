<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscription';
    /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
 protected $fillable = [
     'email'
 ];

 /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
 protected $hidden = [];

 /**
  * The attributes that should be cast to native types.
  *
  * @var array
  */

}
