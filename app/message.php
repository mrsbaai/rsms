<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    //
    protected $fillable = ['message','sender','receiver','date','is_public'];
    protected $table = "messages";
}
