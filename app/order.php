<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{

	 protected $fillable = [
       
        'id','date','starttime', 'endtime', 'totaltime','totalbalance','ustadId','status','service','studentId'
                ];

}
