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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'email' => false,
    'update' => false,
    'request' => false,
]);


Route::resource('courses', 'CourseController');

Route::get('/searchCourse', 'CourseController@search')->name('courses.search');

Route::get('/filterTypeCourse', 'CourseController@filterType')->name('courses.typeFilter');

Route::get('/filterStatusCourse', 'CourseController@filterStatus')->name('courses.statusFilter');

Route::post('/upload', 'FileController@uploadFile')->name('uploadFile');

Route::delete('/deleteFile/{id}/{courseTranslationId}', 'FileController@deleteFile')->name('deleteFile');

Route::post('/downloadFile/{id}', 'FileController@downloadFile')->name('downloadFile');

Route::post('/uploadXML', 'FileController@uploadXMLFile')->name('uploadXMLFile');

Route::get('/oauth', 'DevelopersController@index')->middleware('mainAdmin')->name('dashboard');

Route::group(['middleware' => ['admin']], function () {

    Route::resource('users', 'UserController');

    Route::get('/baseCourse', 'CourseController@showBaseCourse')->name('showBaseCourse');

});
