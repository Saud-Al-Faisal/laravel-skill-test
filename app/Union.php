<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Union extends Model
{
    protected $table = "unions";

    public function upazila()
    {
        return $this->belongsTo(Upazila::class)->select('id', 'upazila_name', 'district_id');
    }

    public static function allUnion()
    {
        return self::orderBy('union_name', 'asc')->get(['id', 'union_name','upazila_id']);
    }
    public static function allUnionaByUpazila($upazila_id)
    {
        return self::orderBy('union_name', 'asc')->where('upazila_id', $upazila_id)->get(['id', 'union_name','upazila_id']);
    }

    public static function unionsByDivision($div_id)
    {
        return DB::table('districts')
            ->select('unions.id', 'unions.union_name', 'unions.upazila_id')
            ->join('upazilas', 'upazilas.district_id','=' ,'districts.id')
            ->join('unions', 'upazilas.id', '=', 'unions.upazila_id')
            ->where('districts.division_id', $div_id)->get();
    }

}
