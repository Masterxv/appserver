<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Comments extends Model
{
    //
    protected $fillable = [
        'id','postId','userId','time','text'
    ];
}
