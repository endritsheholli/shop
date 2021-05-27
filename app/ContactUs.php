<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    //protected $table = 'contact_us';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
     'name,email,phone,company,message'
 ];


    //
}
