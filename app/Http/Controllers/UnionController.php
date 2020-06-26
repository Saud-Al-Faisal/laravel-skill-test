<?php

namespace App\Http\Controllers;

use App\District;
use App\Division;
use App\Union;
use App\Upazila;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        $divisions = Division::allDivision();
        $districts = District::allDistrict();
        $upazilas = Upazila::allUpazila();
        if ($request->ajax()) {

            $filter_query = $request->get('filterby');
            $filter_query = str_replace(" ", "%", $filter_query);
            $data = Union::with('upazila.district.division')->orderBy('union_name', 'asc')
                ->where('id', 'like', '%' . $filter_query . '%')
                ->orWhere(function ($q) use ($filter_query) {
                    $q->whereHas('upazila', function ($query1w) use ($filter_query) {
                         $query1w->whereHas('district', function ($que) use ($filter_query) {
                            return  $que->whereHas('division',function ($ques) use ($filter_query) {
                                return $ques->where('division_name', 'like', '%' . $filter_query . '%');
                            })
                            ->orWhereDoesntHave('division')
                             ->orWhere('district_name', 'like', '%' . $filter_query . '%');
                        })
                        ->orWhere('upazila_name', 'like', '%' . $filter_query . '%')
                        ->orWhereDoesntHave('district');
                    })->orWhereDoesntHave('upazila');
                })
                ->orWhere('union_name', 'like', '%' . $filter_query . '%')
                ->paginate($limit);
            return view('areas.unions.union-table', compact('data', 'divisions', 'districts','upazilas'));
        } else {
            $data = Union::with('upazila.district.division')->paginate($limit);
            return view('areas.unions.union', compact('data', 'divisions', 'districts','upazilas'));
        }

    }
    public function insert(Request $request)
    {
        $request->validate([
            'upazila' => 'required|integer',
            'union_name' => 'required|string|min:3|max:255',
        ]);
        $newDev = new Union();
        $newDev->union_name = $request->union_name;
        $newDev->upazila_id = $request->upazila;
        if ($newDev->save()) {
            return [true, 'New Union has been saved successfully'];
        } else {
            return [false, 'error happend'];
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            Union::findOrFail($request->id)
                ->delete();
            return 'Data has beed deleted successfully';
        }
    }
    public function update(Request $request)
    {

        $request->merge(array(trim($request->column_name) => trim($request->column_value)));
        if ($request->column_name == 'union_name') {
            $request->validate([
                $request->column_name => 'required|string|max:255|min:3',
            ]);
        }
        if ($request->column_name == 'upazila_id') {
            $request->validate([
                $request->column_name => 'required|integer',
            ]);
        }

        if ($request->ajax()) {
            $data = array(
                $request->column_name => trim($request->column_value),
            );
            Union::where('id', $request->id)
                ->update($data);
            return 'Data has beed updated successfully';
        }

    }
    public function unionByUpazila(Request $request)
    {
        if ($request->ajax()) {
            $unions = Union::allUnionaByUpazila($request->upazila_id);
            return view('areas.unions.union-select', compact('unions'));
        }
    }
}
