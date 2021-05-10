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

/**
 * authorization middleware for protected routes; login required
 **/ 
Route::middleware(['auth'])->group(function() {
   
  // Cashier routes
  Route::get('/cashier', 'Cashier\CashierController@index');
  Route::get('/cashier/getMenuByCategory/{category_id}', 'Cashier\CashierController@getMenuByCategory');
  Route::get('/cashier/getTables', 'Cashier\CashierController@getTables');
  Route::post('/cashier/orderFood', 'Cashier\CashierController@orderFood');
  Route::get('/cashier/getSaleDetailsByTable/{table_id}', 'Cashier\CashierController@getSaleDetailsByTable');
  Route::post('/cashier/confirmOrderStatus', 'Cashier\CashierController@confirmOrderStatus');
  Route::post('/cashier/deleteSaleDetail', 'Cashier\CashierController@deleteSaleDetail'); // ajax
  Route::post('/cashier/savePayment', 'Cashier\CashierController@savePayment'); // ajax
  // sales receipt
  Route::get('/cashier/showReceit/{saleId}', 'Cashier\CashierController@showReceipt'); // redirect after payment of sale
});

/**
 * authorization middleware for protected routes; restriction to admin only
 **/ 
Route::middleware(['auth', 'VerifyAdmin'])->group(function() { 
  
  // management index
    Route::get('/management', function() { 
      return view('management.index');
    }); 
  
  // resource routes for Management
  Route::resource('management/category', 'Management\CategoryController'); // URI, then folder where the resource controller lives
  Route::resource('management/menu', 'Management\MenuController'); 
  Route::resource('management/table', 'Management\TableController'); 
  Route::resource('management/user', 'Management\UserController'); 
  
  // report
  Route::get('/report', 'Report\ReportController@index');
  Route::get('/report/show', 'Report\ReportController@show');
  // Excel export
  Route::get('/report/show/export', 'Report\ReportController@export');
});




