<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);


Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['admin']], function () {
    Route::get('/designations', 'DesignationController@index')->name('designation.index');
    Route::post('/designations/insert', 'DesignationController@insert')->name('designation.insert');
    Route::put('/designations/update', 'DesignationController@update')->name('designation.update');
    Route::delete('/designations/delete', 'DesignationController@delete')->name('designation.delete');

    Route::get('/divisions', 'DivisionController@index')->name('division.index');
    Route::post('/divisions/insert', 'DivisionController@insert')->name('division.insert');
    Route::put('/divisions/update', 'DivisionController@update')->name('division.update');
    Route::delete('/divisions/delete', 'DivisionController@delete')->name('division.delete');

    Route::get('/districts', 'DistrictController@index')->name('district.index');
    Route::post('/districts/insert', 'DistrictController@insert')->name('district.insert');
    Route::put('/districts/update', 'DistrictController@update')->name('district.update');
    Route::delete('/districts/delete', 'DistrictController@delete')->name('district.delete');

    Route::get('/districts/by-division', 'DistrictController@districtsByDivision')->name('district.by.division');

    Route::get('/upazilas', 'UpazilaController@index')->name('upazila.index');
    Route::post('/upazilas/insert', 'UpazilaController@insert')->name('upazila.insert');
    Route::put('/upazilas/update', 'UpazilaController@update')->name('upazila.update');
    Route::delete('/upazilas/delete', 'UpazilaController@delete')->name('upazila.delete');


    Route::get('/unions', 'UnionController@index')->name('union.index');
    Route::post('/unions/insert', 'UnionController@insert')->name('union.insert');
    Route::put('/unions/update', 'UnionController@update')->name('union.update');
    Route::delete('/unions/delete', 'UnionController@delete')->name('union.delete');

    
    Route::get('/users', 'UserController@index')->name('user.index');
    Route::post('/users/insert', 'UserController@insert')->name('user.insert');
    Route::put('/users/update', 'UserController@update')->name('user.update');
    Route::put('/users/update/info', 'UserController@updateInfo')->name('user.update.info');
    Route::delete('/users/delete', 'UserController@delete')->name('user.delete');

});
Route::get('/area-users', 'AreaHeadController@index')->name('area.user.index');


Route::get('/unions/by-upazila', 'UnionController@unionByUpazila')->name('union.by.upazila');

Route::get('/upazilas/by-district', 'UpazilaController@upazilaByDistrict')->name('upazila.by.district');

