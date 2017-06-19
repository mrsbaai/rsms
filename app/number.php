<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class number extends Model
{
    protected $table = "numbers";

    public function messages()
    {
        return $this->hasMany('App\message');
    }
}


