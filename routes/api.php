<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@authenticate');
Route::post('logout', 'AuthController@logout');

Route::prefix('v1')->group(function () {
    Route::get('book', 'BookController@index');
    Route::post('book', 'BookController@store');
    Route::delete('book/{id}', 'BookController@destroy');
    Route::put('book/{id}', 'BookController@update');
    Route::patch('book/{id}', 'BookController@update');
    Route::post('file', 'FileController@fileUpload');

    Route::group(['middleware' => ['header']], function() {
        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::get('employee', 'EmployeeController@index');
            Route::get('employee/{id}', 'EmployeeController@show');
            Route::post('employee', 'EmployeeController@store');
            Route::delete('employee/{id}', 'EmployeeController@destroy');
            Route::patch('employee/{id}', 'EmployeeController@update');
        });
    });

    Route::group(['middleware' => ['header_date']], function() {
        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::get('student', 'StudentController@index');
            Route::get('student/{id}', 'StudentController@show');
            Route::post('student', 'StudentController@store');
            Route::delete('student/{id}', 'StudentController@destroy');
            Route::patch('student/{id}', 'StudentController@update');
        });
    });

});

Route::prefix('v2')->group(function () {
    Route::get('book', 'BookV2Controller@index');
    Route::post('book', 'BookV2Controller@store');
    Route::delete('book/{id}', 'BookV2Controller@destroy');
    Route::put('book/{id}', 'BookV2Controller@update');
    Route::patch('book/{id}', 'BookV2Controller@update');
});

Route::prefix('v3')->group(function () {
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('jwt/book', 'BookV3Controller@indexJWT');
        Route::post('jwt/book', 'BookV3Controller@storeJWT');
    });
    Route::post('jwt/token', 'AuthController@generateToken');

    // Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('basic/book', 'BookV3Controller@index');
        Route::post('basic/book', 'BookV3Controller@store');
    // });
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/test', function () {
        return "<h2>".config('app.name')."</h2>";
    });
});

