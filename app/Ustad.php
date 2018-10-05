<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ustad extends Model
{
    protected $fillable = [
        'id','username','name', 'email', 'password','firebaseid','logo','active','phone','category',
        'code',
        'info','price','skills'
    ];
}
