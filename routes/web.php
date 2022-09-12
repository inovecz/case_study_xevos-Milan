<?php

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

/* Route::get('/', function () {
    return view('index');
}); */
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'EmployeeController@index')->name('employee.index');
   /*  Route::get('/storeJson', 'EmployeeController@storeJson')->name('employee.storeJson'); */
});

Route::group(['namespace' => 'App\Http\Controllers\API', 'prefix' => 'api'], function () {
    Route::get('/employees/all', 'EmployeeController@index')->name('api.employees.all');
    Route::get('/employees/reload', 'EmployeeController@storeJson')->name('api.employees.storeJson');
    Route::post('/employees/destroy/{id}', 'EmployeeController@destroy')->name('api.employees.destroy');
    Route::post('/employees/update/{id}', 'EmployeeController@update')->name('api.employees.update');
    Route::get('/employees/salaries', 'EmployeeController@storeSalaries')->name('api.employees.storeSalaries');
});
