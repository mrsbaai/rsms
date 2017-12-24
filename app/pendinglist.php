<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pendinglist extends Model
{
    //
    protected $fillable = ['sendingdate','email','subject','heading1','heading2','heading3','heading4', 'text1', 'text2', 'text3', 'text4', 'button1', 'button2', 'button3', 'buttonURL1', 'buttonURL2', 'buttonURL3', 'img1', 'img2'];
    protected $table = "pendinglist";
}

