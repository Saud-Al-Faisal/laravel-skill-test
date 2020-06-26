<?php

namespace App\Http\Controllers;

use App\District;
use App\Division;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        $divisions = Division::allDivision();
        if ($request->ajax()) {

            $filter_query = $request->get('filterby');
            $filter_query = str_replace(" ", "%", $filter_query);
            $data = District::with('division')->orderBy('district_name', 'asc')
                ->where('id', 'like', '%' . $filter_query . '%')
                ->orWhere(function($q) use ($filter_query){
                    $q->whereHas('division', function ($query1w) use ($filter_query) {
                        return $query1w->where('division_name', 'like', '%' . $filter_query . '%');
                      });
                })
    
                ->orWhere('district_name', 'like', '%' . $filter_query . '%')
                ->paginate($limit);
            return view('areas.districts.district-table', compact('data','divisions'));
        } else {
            $data = District::with('division')->paginate($limit);
          
            return view('areas.districts.district', compact('data','divisions'));
        }

    }
    public function insert(Request $request)
    {

        $request->validate([
            'district_name' => 'required|string|max:255|min:3|unique:districts,district_name',
            'division' => 'required|integer',
        ]);
        $newDev = new District();
        $newDev->district_name = $request->district_name;
        $newDev->division_id = $request->division;
        if ($newDev->save()) {
            return [true, 'New District has been saved successfully', $newDev->load('division')];
        } else {
            return [false, 'error happend'];
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            District::findOrFail($request->id)
                ->delete();
            return 'Data has beed deleted successfully';
        }
    }
    public function update(Request $request)
    {
        $request->merge(array(trim($request->column_name) => trim($request->column_value)));
        if($request->column_name == 'district_name'){
             $request->validate([
                 $request->column_name => 'required|string|max:255|min:3|unique:districts,district_name'
             ]);
        }
        if($request->column_name == 'division_id'){
            $request->validate([
                $request->column_name => 'required|integer'
            ]);
        }
     
        if ($request->ajax()) {
            $data = array(
                $request->column_name => trim($request->column_value),
            );
            District::where('id', $request->id)
                ->update($data);
            return 'Data has beed updated successfully';
        }

    }
    public function districtsByDivision(Request $request){
        if ($request->ajax()) {
            $districts =  District::allDistrictByDivision($request->division_id);
            return view('areas.upazilas.district-select', compact('districts'));
        }
    }
}
