<?php

namespace App\Http\Controllers;

use App\Designation;
use App\District;
use App\Division;
use App\Union;
use App\Upazila;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        
        $divisions = Division::allDivision();
        $districts = District::allDistrict();
        $upazilas = Upazila::allUpazila();
        $unions = Union::allUnion();
        $designations = Designation::allDesignation();
        if ($request->ajax()) {

            $filter_query = $request->get('filterby');
            $filter_query = str_replace(" ", "%", $filter_query);
            $data = User::with(['union', 'upazila', 'district', 'division', 'designation'])
                ->where('designation_id', '!=', 0)
                ->orderBy('name', 'asc');

             if($filter_query!=''){
                $data ->where('id', 'like', '%' . $filter_query . '%')
                ->orWhere('name', 'like', '%' . $filter_query . '%')
                ->orWhere('email', 'like', '%' . $filter_query . '%')
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('designation', function ($q1) use ($filter_query) {
                        return $q1->where('designation_name', 'like', '%' . $filter_query . '%');
                    });
                })
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('division', function ($q1) use ($filter_query) {
                        return $q1->where('division_name', 'like', '%' . $filter_query . '%');
                    });
                })
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('district', function ($q1) use ($filter_query) {
                        return $q1->where('district_name', 'like', '%' . $filter_query . '%');
                    });
                })
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('upazila', function ($q1) use ($filter_query) {
                        return $q1->where('upazila_name', 'like', '%' . $filter_query . '%');
                    });
                })
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('union', function ($q1) use ($filter_query) {
                        return $q1->where('union_name', 'like', '%' . $filter_query . '%');
                    });
                });
            }
            $data->paginate($limit)->get();
            return view('users.users.user-table', compact('data', 'divisions', 'districts', 'upazilas', 'unions', 'designations'));
        } else {
            $data = User::with(['union', 'upazila', 'district', 'division', 'designation'])
                ->where('designation_id', '!=', 0)->orderBy('name', 'asc')->paginate($limit);

            return view('users.users.user', compact('data', 'divisions', 'districts', 'upazilas', 'unions', 'designations'));
        }
    }
    public function insert(Request $request)
    {

        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'designation_name' => 'required|string|max:255',
            'designation' => 'required|integer',
            'division' => 'integer|nullable',
        ]);

        if ($request->designation_name == "Division Head") {
            $request->validate([
                'district' => 'in:',
                'upazila' => 'in:',
                'union' => 'in:',
            ]);
        }
        if ($request->designation_name == "District Head") {
            $request->validate([
                'district' => 'integer|required',
                'upazila' => 'in:',
                'union' => 'in:',
            ]);
        }
        if ($request->designation_name == "Upazila Head") {
            $request->validate([
                'district' => 'integer|required',
                'upazila' => 'integer|required',
                'union' => 'in:',
            ]);
        }
        if ($request->designation_name == "Union Head") {
            $request->validate([
                'district' => 'integer|required',
                'upazila' => 'integer|required',
                'union' => 'integer|required',
            ]);
        }
        $newuser = new User();
        $newuser->name = $request->user_name;
        $newuser->email = $request->user_email;
        $newuser->password = Hash::make($request->password);
        $newuser->designation_id = $request->designation;
        $newuser->division_id = $request->division;
        $newuser->district_id = $request->district;
        $newuser->upazila_id = $request->upazila;
        $newuser->union_id = $request->union;
        if ($newuser->save()) {
            return [true, 'New User has been saved successfully'];
        } else {
            return [false, 'error happend'];
        }
    }
    public function update(Request $request)
    {

        $request->merge(array(trim($request->column_name) => trim($request->column_value)));
        if ($request->column_name == 'name') {
            $request->validate([
                $request->column_name => 'required|string|max:255|min:3',
            ]);
        }
        if ($request->column_name == 'email') {
            $request->validate([
                $request->column_name => 'required|email|string|max:255|unique:users,email',
            ]);
        }
        if ($request->column_name == 'password') {
            $request->validate([
                $request->column_name => 'required|string|max:255|min:8',
            ]);
            $request->column_value = Hash::make($request->column_value);
        }

        if ($request->ajax()) {
            $data = array(
                $request->column_name => trim($request->column_value),
            );
            User::where('id', $request->id)
                ->update($data);
            return 'Data has beed updated successfully';
        }

    }
    public function updateInfo(Request $req)
    {
        $req->validate([
            // 'password' => 'required|string|min:8|confirmed',
            'designation' => 'required|integer',
            'designation_name' => 'required|string',
            'division' => 'integer|required',
            'id' => 'integer|required',
        ]);
        if ($req->designation_name == "Division Head") {
            $req->validate([
                'district' => 'in:',
                'upazila' => 'in:',
                'union' => 'in:',
            ]);
        }
        if ($req->designation_name == "District Head") {
            $req->validate([
                'district' => 'integer|required',
                'upazila' => 'in:',
                'union' => 'in:',
            ]);
        }
        if ($req->designation_name == "Upazila Head") {
            $req->validate([
                'district' => 'integer|required',
                'upazila' => 'integer|required',
                'union' => 'in:',
            ]);
        }
        if ($req->designation_name == "Union Head") {
            $req->validate([
                'district' => 'integer|required',
                'upazila' => 'integer|required',
                'union' => 'integer|required',
            ]);
        }
        $user = User::findOrFail($req->id);
        $user->designation_id = $req->designation;
        $user->division_id = $req->division;
        $user->district_id = $req->district;
        $user->upazila_id = $req->upazila;
        $user->union_id = $req->union;
        if ($user->update()) {
            return 'the User has been updated successfully';
        } else {
            return 'error happend';
        }

    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            User::findOrFail($request->id)
                ->delete();
            return 'Data has beed deleted successfully';
        }
    }
}
