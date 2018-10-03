<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $fillable = [
        'id','username','name', 'email', 'password','firebaseid','logo','active','phone','birthday','address','code'
    ];
}
