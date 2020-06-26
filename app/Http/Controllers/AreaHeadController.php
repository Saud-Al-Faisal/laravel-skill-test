<?php

namespace App\Http\Controllers;

use App\Designation;
use App\District;
use App\Division;
use App\Union;
use App\Upazila;
use App\User;
use Illuminate\Http\Request;

class AreaHeadController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        if (User::verifyDivisionHead()) {
            $division_id = auth()->user()->division_id;
            $districts = District::allDistrictByDivision($division_id)->load(['upazilas.unions']);
            $districtTds = $districts->pluck('id');
            $designations = Designation::allBelowUserRank(auth()->user()->designation->rank);

            $current_level_col = 'division_id';
            $lowaer_level_col = 'district_id';

            $unions = Union::unionsByDivision($division_id);
            $division = Division::with('upazilas')->findOrFail($division_id);
            $upazilas = $division->upazilas ?? null;

            if ($request->ajax()) {

                $filter_query = $request->get('filterby');
                $filter_query = str_replace(" ", "%", $filter_query);

                $data = $this->dataQueryByAjax($current_level_col, $division_id, $lowaer_level_col, $districtTds, $limit, $filter_query);
                return view('users.users.area-user-table', compact('data', 'districts', 'upazilas', 'unions', 'designations'));
            } else {
                $data = $this->dataQueryNormally($current_level_col, $division_id, $lowaer_level_col, $districtTds, $limit);

                return view('users.users.area-user', compact('data', 'districts', 'upazilas', 'unions', 'designations'));
            }
        } elseif (User::verifyDistrictHead()) {
            $district_id = auth()->user()->district_id;
            $upazilas = Upazila::allUpazilaByDistrict($district_id)->load(['unions']);
            $upazilaIds = $upazilas->pluck('id');
            $designations = Designation::allBelowUserRank(auth()->user()->designation->rank);

            $current_level_col = 'district_id';
            $lowaer_level_col = 'upazila_id';

            $district = District::with('unions')->findOrFail($district_id);
            $unions = $district->unions ?? null;

            if ($request->ajax()) {

                $filter_query = $request->get('filterby');
                $filter_query = str_replace(" ", "%", $filter_query);

                $data = $this->dataQueryByAjax($current_level_col, $district_id, $lowaer_level_col, $upazilaIds, $limit, $filter_query);
                return view('users.users.area-user-table', compact('data', 'upazilas', 'unions', 'designations'));
            } else {
                $data = $this->dataQueryNormally($current_level_col, $district_id, $lowaer_level_col, $upazilaIds, $limit);

                return view('users.users.area-user', compact('data', 'upazilas', 'unions', 'designations'));
            }
        } elseif (User::verifyUpazilaHead()) {
            $upazila_id = auth()->user()->upazila_id;
            $designations = Designation::allBelowUserRank(auth()->user()->designation->rank);

            $unions = Union::allUnionaByUpazila($upazila_id);
            $unionIds = $unions->pluck('id');
            $current_level_col = 'upazila_id';
            $lowaer_level_col = 'union_id';

            if ($request->ajax()) {

                $filter_query = $request->get('filterby');
                $filter_query = str_replace(" ", "%", $filter_query);

                $data = $this->dataQueryByAjax($current_level_col, $upazila_id, $lowaer_level_col, $unionIds, $limit, $filter_query);
                return view('users.users.area-user-table', compact('data', 'unions', 'designations'));
            } else {
                $data = $this->dataQueryNormally($current_level_col, $upazila_id, $lowaer_level_col, $unionIds, $limit);

                return view('users.users.area-user', compact('data', 'unions', 'designations'));
            }
        } elseif (User::verifyUnionHead()) {
            $union_id = auth()->user()->union_id;
            $designations = Designation::allBelowUserRank(auth()->user()->designation->rank);

            if ($request->ajax()) {
                $filter_query = $request->get('filterby');
                $filter_query = str_replace(" ", "%", $filter_query);
                $data = User::with(['union', 'upazila', 'district', 'division', 'designation'])
                    ->where('designation_id', '!=', 0)
                    ->orderBy('name', 'asc');
                if ($filter_query != '') {

                    $data->where('id', 'like', '%' . $filter_query . '%')
                        ->orWhere('name', 'like', '%' . $filter_query . '%')
                        ->orWhere('email', 'like', '%' . $filter_query . '%')
                        ->where('union_id', '=', $union_id)
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
                return $data->paginate($limit);

                return view('users.users.area-user-table', compact('data', 'designations'));
            } else {

                $data = User::with(['union', 'upazila', 'district', 'division', 'designation'])
                    ->where('designation_id', '!=', 0)
                    ->where('union_id', '=', $union_id)
                    ->orderBy('name', 'asc')->paginate($limit);

                return view('users.users.area-user', compact('data', 'designations'));
            }
        }
    }
    public function dataQueryNormally($current_level_col, $current_level_id, $lowaer_level_col, $lowaer_level_ids, $limit)
    {
        return $data = User::with(['union', 'upazila', 'district', 'division', 'designation'])
            ->where('designation_id', '!=', 0)
            ->orderBy('name', 'asc')
            ->where($current_level_col, '=', $current_level_id)
            ->whereIn($lowaer_level_col, $lowaer_level_ids)
            ->paginate($limit);
    }
    public function dataQueryByAjax($current_level_col, $current_level_id, $lowaer_level_col, $lowaer_level_ids, $limit, $filter_query)
    {

        $data = User::with(['union', 'upazila', 'district', 'division', 'designation'])
            ->where('designation_id', '!=', 0)
            ->where($current_level_col, '=', $current_level_id)
            ->whereIn($lowaer_level_col, $lowaer_level_ids)
            ->orderBy('name', 'asc');
        if ($filter_query != '') {

            $data->where('id', 'like', '%' . $filter_query . '%')
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
        return $data->paginate($limit);
    }
}
