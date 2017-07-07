<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    //
    protected $fillable = ['is_support','message','subject','user_id','email','name','is_responded'];
    protected $table = "contacts";
}

