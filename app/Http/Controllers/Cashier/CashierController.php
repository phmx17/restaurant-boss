<?php

namespace App\Http\Controllers\Cashier;

use App\Menu;
use App\Sale;
use App\Table;
use App\Category;
use App\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\support\facades\Auth; // used to get user_id to create Sale

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
      '<button class="btn btn-primary btn-table" data-id="'.$table->id.'" data-name="'.$table->name.'">
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
    // dd($menus);

    // create markup for #list-menu container 
    $html = '';
    foreach($menus as $menu) {
      $html .= '
      <div class="col-md-3 text-center">
        <a class="btn btn-outline-secondary btn-menu" data-id="'.$menu->id.'">
          <img class="img-fluid" src="'.url('/menu_images/'.$menu->image).'" />
          <br>
          '.$menu->name.'
          <br>
          $'.number_format($menu->price).'
        </a>
      </div>
      ';
    } // .btn-menu is for targeting and not a bootstrap class
      return $html;    
  }

  public function orderFood(Request $request)
  {
    $menu = Menu::find($request->menu_id);
    $table_id = $request->table_id;
    $table_name = $request->table_name;
    $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first(); //find this unpaid table in Sale model
    // if there is no sale yet for the selected table, create new Sale record and populate
    if(!$sale) {
      $user = Auth::user();
      $sale = new Sale();
      $sale->table_id = $table_id;
      $sale->table_name = $table_name;
      $sale->user_id = $user->id;
      $sale->user_name = $user->name;
      $sale->save();
      $sale_id = $sale->id;
      // update table status
      $table = Table::find($table_id);
      $table->status = 'unavailable';
      $table->save();
    } else {
      // there is a sale already for the selected table
      $sale_id = $sale->id;
    }

    // add ordered menu to the sale_details
    $saleDetail = new SaleDetail();
    $saleDetail->sale_id = $sale_id;
    $saleDetail->menu_id = $menu->id;
    $saleDetail->menu_name = $menu->name;
    $saleDetail->menu_price = $menu->price;
    $saleDetail->quantity = $request->quantity;
    $saleDetail->save();
    // update total price in Sale
    $sale->total_price = $sale->total_price + ($request->quantity * $menu->price);
    $sale->save();
    
    $html = $this->getSaleDetails($sale_id);
    return $html; // call function for markup creation
  }

  /**
   * create markup for sale details
   */
  private function getSaleDetails($sale_id)
  {
    // list all sales detail
    $html = '<p>Sale ID: '.$sale_id.'</p>';
    $saleDetails = SaleDetail::where('sale_id', $sale_id)->get(); // $sale_id is current (local) sale id, see above
    // concat more markup
    $html .= '<div class="table-responsive-md" style="overflow-y: scroll; height: 400px; border: 1px solid #343A40;">
    <table class="table table-striped table-dark">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Menu</th>
        <th scope="col">Quantity</th>
        <th scope="col">Price</th>
        <th scope="col">Total</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>';

    // loop through sale details and create the table data
    foreach($saleDetails as $saleDetail) {
      $html .= '
      <tr>
        <td>'.$saleDetail->menu_id.'</td>
        <td>'.$saleDetail->menu_name.'</td>
        <td>'.$saleDetail->quantity.'</td>
        <td>'.$saleDetail->menu_price.'</td>
        <td>'.($saleDetail->menu_price * $saleDetail->quantity).'</td>
        <td>'.$saleDetail->status.'</td>      
      </tr>
      ';
    }
    $html .= '</tbody></table></div>'; 
    return $html;
  }
}