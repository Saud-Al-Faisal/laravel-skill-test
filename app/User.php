<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'designation_id', 'division_id', 'district_id', 'union_id', 'upazila_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class)->select('id', 'designation_name','rank');
    }
    public function division()
    {
        return $this->belongsTo(Division::class)->select('id', 'division_name');
    }
    public function district()
    {
        return $this->belongsTo(District::class)->select('id', 'district_name');
    }
    public function upazila()
    {
        return $this->belongsTo(Upazila::class)->select('id', 'upazila_name');
    }
    public function union()
    {
        return $this->belongsTo(Union::class)->select('id', 'union_name');
    }
    public static function verifyAdmin()
    {
        if (Auth::check()) {
            if (Auth::user()->designation->designation_name == 'Admin' && Auth::user()->designation_id == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function verifyDivisionHead()
    {
        if (
            auth()->user()->designation->designation_name == "Division Head" &&
            auth()->user()->division_id != null &&
            auth()->user()->district_id == null &&
            auth()->user()->upazila_id == null &&
            auth()->user()->union_id == null) {
            return true;
        } else {
            return false;
        }
    }
    public static function verifyDistrictHead()
    {
        if (
            auth()->user()->designation->designation_name == "District Head" &&
            auth()->user()->division_id != null &&
            auth()->user()->district_id != null &&
            auth()->user()->upazila_id == null &&
            auth()->user()->union_id == null) {
            return true;
        } else {
            return false;
        }
    }
    public static function verifyUpazilaHead()
    {
        if (
            auth()->user()->designation->designation_name == "Upazila Head" &&
            auth()->user()->division_id != null &&
            auth()->user()->district_id != null &&
            auth()->user()->upazila_id != null &&
            auth()->user()->union_id == null) {
            return true;
        } else {
            return false;
        }
    }
    public static function verifyUnionHead()
    {
        if (
            auth()->user()->designation->designation_name == "Union Head" &&
            auth()->user()->division_id != null &&
            auth()->user()->district_id != null &&
            auth()->user()->upazila_id != null &&
            auth()->user()->union_id != null) {
            return true;
        } else {
            return false;
        }
    }
}
