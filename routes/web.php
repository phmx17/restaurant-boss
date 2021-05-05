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



// resource routes for Management
Route::resource('management/category', 'Management\CategoryController'); // URI, then folder where the resource controller lives
Route::resource('management/menu', 'Management\MenuController'); // URI, then folder where the resource controller lives
Route::resource('management/table', 'Management\TableController'); // URI, then folder where the resource controller lives



