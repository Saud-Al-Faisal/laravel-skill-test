<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $table = "upazilas";

    public static function allUpazila()
    {
      return self::orderBy('upazila_name','asc')->get(['id','upazila_name','district_id']);
    }

    public function district(){
        return $this->belongsTo(District::class)->select('id','district_name','division_id');
    } 

    public static function allUpazilaByDistrict($district_id)
    {
      return self::orderBy('upazila_name','asc')->where('district_id',$district_id)->get(['id','upazila_name','district_id']);
    }
    public function unions()
    {
      return $this->hasMany(Union::class)->select('id','union_name','upazila_id');
    }
}
 