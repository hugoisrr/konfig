<?php

use Illuminate\Support\Facades\Route;

Route::post('/getCourses', 'CourseApiController@index')->name('getCourses');
Route::post('/getBaseCourse', 'CourseApiController@baseCourse')->name('getBaseCourse');
Route::post('/checkPermission', 'CheckPermissionController@index')->name('checkPermission');
Route::post('/downloadBaseCourse', 'ZipController@downloadBaseCourse')->name('downloadBaseCourse');
Route::post('/downloadCourse', 'ZipController@downloadCourse')->name('downloadCourse');
Route::post('/sendFeedback', 'SendFeedbackController@sendFeedback')->name('sendFeedback');
