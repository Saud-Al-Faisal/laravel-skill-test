<?php

namespace App\Http\Controllers;

use App\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        if ($request->ajax()) {

            $filter_query = $request->get('filterby');
            $filter_query = str_replace(" ", "%", $filter_query);
            $data = Designation::orderBy('designation_name', 'asc')
                ->where('id', 'like', '%' . $filter_query . '%')
                ->orWhere('designation_name', 'like', '%' . $filter_query . '%')
                ->paginate($limit);
            return view('users.designations.designation-table', compact('data'));
        } else {
            $data = Designation::paginate($limit);
            return view('users.designations.designation', compact('data'));
        }

    }
    public function insert(Request $request)
    {

        $request->validate([
            'designation_name' => 'required|string|max:255|min:3|unique:designations,designation_name',
        ]);
        $newDev = new Designation();
        $newDev->designation_name = $request->designation_name;
        if ($newDev->save()) {
            return [true, 'New Designation has been saved successfully', $newDev];
        } else {
            return [false, 'error happend'];
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            Designation::findOrFail($request->id)
                ->delete();
            return 'Data has beed deleted successfully';
        }
    }
    public function update(Request $request)
    {
        $request->merge(array(trim($request->column_name) => trim($request->column_value)));
        $request->validate([
            $request->column_name => 'required|string|max:255|min:3|unique:designations,designation_name'
        ]);
        if ($request->ajax()) {
            $data = array(
                $request->column_name => trim($request->column_value),
            );
            Designation::where('id', $request->id)
                ->update($data);
            return 'Data has beed updated successfully';
        }

    }
}
