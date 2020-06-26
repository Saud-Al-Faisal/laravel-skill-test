<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Division extends Model
{
    protected $table = "divisions";
    public static function allDivision()
    {
      return self::orderBy('division_name','asc')->get(['id','division_name']);
    }
    public function districts()
    {
      return $this->hasMany(District::class)->select('id','district_name','division_id');
    }

    public function upazilas()
    {
        return $this->hasManyThrough(Upazila::class, District::class);
    }
}
