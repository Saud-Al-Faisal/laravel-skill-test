<?php

namespace App\Http\Controllers;

use App\District;
use App\Division;
use App\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        
        $divisions = Division::allDivision();
        $districts = District::allDistrict();
        if ($request->ajax()) {

            $filter_query = $request->get('filterby');
            $filter_query = str_replace(" ", "%", $filter_query);
            $data = Upazila::with('district')->orderBy('upazila_name', 'asc')
                ->where('id', 'like', '%' . $filter_query . '%')
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('district', function ($query1w) use ($filter_query) {
                        return $query1w->whereHas('division', function ($que) use ($filter_query) {
                            return $que->where('division_name', 'like', '%' . $filter_query . '%');
                        })
                            ->orWhere('district_name', 'like', '%' . $filter_query . '%')
                            ->orWhereDoesntHave('division');
                    })->orWhereDoesntHave('district');
                })
                ->orWhere('upazila_name', 'like', '%' . $filter_query . '%')
                ->paginate($limit);
            return view('areas.upazilas.upazila-table', compact('data', 'divisions', 'districts'));
        } else {
            $data = Upazila::with('district')->paginate($limit);
            return view('areas.upazilas.upazila', compact('data', 'divisions', 'districts'));
        }

    }
    public function insert(Request $request)
    {

        $request->validate([
            'district' => 'required|integer',
            'upazila_name' => 'required|string|min:3|max:255',
        ]);
        $newDev = new Upazila();
        $newDev->district_id = $request->district;
        $newDev->upazila_name = $request->upazila_name;
        if ($newDev->save()) {
            return [true, 'New Upazila has been saved successfully', $newDev->load('district.division')];
        } else {
            return [false, 'error happend'];
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            Upazila::findOrFail($request->id)
                ->delete();
            return 'Data has beed deleted successfully';
        }
    }
    public function update(Request $request)
    {

        $request->merge(array(trim($request->column_name) => trim($request->column_value)));
        if ($request->column_name == 'upazila_name') {
            $request->validate([
                $request->column_name => 'required|string|max:255|min:3',
            ]);
        }
        if ($request->column_name == 'district_id') {
            $request->validate([
                $request->column_name => 'required|integer',
            ]);
        }

        if ($request->ajax()) {
            $data = array(
                $request->column_name => trim($request->column_value),
            );
            Upazila::where('id', $request->id)
                ->update($data);
            return 'Data has beed updated successfully';
        }
    }
    public function upazilaByDistrict(Request $request)
    {
        if ($request->ajax()) {
            $upazilas = Upazila::allUpazilaByDistrict($request->district_id);
            return view('areas.unions.upazila-select', compact('upazilas'));
        }
    }

}
