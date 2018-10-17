<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'id','text','fromUserId','toUserId','title','postId','userType','type','time'
    ];
}
