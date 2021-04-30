<?php

namespace App\Http\Controllers\Cashier;

use App\Menu;
use App\Table;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashierController extends Controller
{
  public function index() 
  {
    $categories = Category::all();    
    return view('cashier.index')->with('categories', $categories);
  }
    public function getTables() // access from jQuery in index page on button click
  {
    // prepare markup to send to index view for toggling show / hide tables
    $tables = Table::all();
    $html ='';
    foreach($tables as $table) {
      $html .= '<div class="col-md-2 mb-4">';
      $html .= 
      '<button class="btn btn-primary btn-table" data-id="'.$table->id.'">
        <img class="img-fluid" width="100px" src="'.url('/images/chairs-table.svg').'" />
        <br>
        <span class="badge badge-success">'.$table->name.'</span>
      </button>';
      $html .= '</div>';
    }
    return $html;
  }

  public function getMenuByCategory($category_id) 
  {
    $menus = Menu::where('category_id', $category_id)->get();
    // create markup
    $html = '';
    foreach($menus as $menu) {
      $html .= '
      <div class="col-md-3 text-center">
        <a class="btn btn-outline-secondary" data-id="'.$menu->id.'">
          <img class="img-fluid" src="'.url('/menu_images/'.$menu->image).'" />
          <br>
          '.$menu->name.'
          <br>
          $'.number_format($menu->price).'
        </a>
      </div>
      ';
      return $html;

    }
  }
}