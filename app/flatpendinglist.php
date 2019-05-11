<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class flatpendinglist extends Model
{
    //
    protected $fillable = ['sendingdate','email','subject','from_email','from_name','html'];
    protected $table = "flatpendinglist";
}

