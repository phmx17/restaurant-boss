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

// resource routes for Management
Route::resource('management/category', 'Management\CategoryController'); // URI, then folder where the resource controller lives
Route::resource('management/menu', 'Management\MenuController'); // URI, then folder where the resource controller lives
Route::resource('management/table', 'Management\TableController'); // URI, then folder where the resource controller lives

// Cashier routes
Route::get('/cashier', function() {
  return view('cashier.index');
});

