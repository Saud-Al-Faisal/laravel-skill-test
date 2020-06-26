<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = "designations";

    public static function allDesignation()
    {
        return self::orderBy('designation_name','asc')->where('id','!=',0)->get(['id','designation_name','rank']);
    }
    public static function allBelowUserRank($rank)
    {
        return self::orderBy('designation_name','asc')->where('rank','<',$rank)->where('id','!=',0)->get(['id','designation_name','rank']);
    }
}
