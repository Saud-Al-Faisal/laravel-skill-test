<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 5;
        if ($request->ajax()) {

            $filter_query = $request->get('filterby');
            $filter_query = str_replace(" ", "%", $filter_query);
            $data = Division::orderBy('division_name', 'asc')
                ->where('id', 'like', '%' . $filter_query . '%')
                ->orWhere('division_name', 'like', '%' . $filter_query . '%')
                ->paginate($limit);
            return view('areas.divisions.division-table', compact('data'));
        } else {
            $data = Division::paginate($limit);
            return view('areas.divisions.division', compact('data'));
        }

    }
    public function insert(Request $request){

        $request->validate([
            'division_name' => 'required|string|max:255|min:3|unique:divisions,division_name'
        ]);
        $newDev = new Division();
        $newDev->division_name = $request->division_name;
        if($newDev->save()){
            return [true,'New Division has been saved successfully',$newDev];
        }else{
            return [false,'error happend'];
        }
    }
    public function delete(Request $request){
        if($request->ajax())
        {
           Division::findOrFail($request->id)
                ->delete();
              return 'Data has beed deleted successfully';
        }
    }
    public function update(Request $request){
        // return $request->all();
        $request->merge(array(trim($request->column_name) => trim($request->column_value)));
        $request->validate([
            $request->column_name => 'required|string|max:255|min:3|unique:divisions,division_name'
        ]);
        if($request->ajax())
        {
            $data = array(
                $request->column_name =>  trim($request->column_value)
            );
            Division::where('id', $request->id)
                ->update($data);
                return 'Data has beed updated successfully';
        }

    }
}
