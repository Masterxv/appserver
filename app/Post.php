<?php

namespace App;
use App\Ustad;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'id','title','text','type','category','userId','time','ustad'
    ];
}
