<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = "districts";

    public static function allDistrict()
    {
      return self::orderBy('district_name','asc')->get(['id','district_name','division_id']);
    }
    public function division(){
        return $this->belongsTo(Division::class)->select('id','division_name');
    }
    public static function allDistrictByDivision($division_id)
    {
      return self::orderBy('district_name','asc')->where('division_id',$division_id)->get(['id','district_name']);
    }
    public function upazilas()
    {
      return $this->hasMany(Upazila::class)->select('id','upazila_name','district_id');
    }
    public function unions()
    {
        return $this->hasManyThrough(Union::class, Upazila::class);
    }
}
  