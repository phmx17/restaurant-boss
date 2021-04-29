<?php

namespace App\Http\Controllers\Cashier;

use App\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashierController extends Controller
{
  public function index() 
  {
    return view('cashier.index');
  }
    public function getTables() // access from jQuery in index page on button click
  {
    $tables = Table::all();
    $html ='';
    foreach($tables as $table) {
      $html .= '<div class="col-md-2 mb-4">';
      $html .= 
      '<button class="btn btn-primary">
        <img class="img-fluid" width="100px" src="'.url('/images/chairs-table.svg').'" />
        <br>
        <span class="badge badge-success">'.$table->name.'</span>
      </button>';
      $html .= '</div>';
    }
    return $html;
  }
}