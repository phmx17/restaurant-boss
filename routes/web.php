<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');  // this goes to HomeController and executes index()

Route::get('/management', function() {
  return view('management.index');
});

Route::resource('management/category', 'Management\CategoryController'); // path then folder where the resource controller lives

Route::resource('management/menu', 'Management\MenuController'); // path then folder where the resource controller lives
