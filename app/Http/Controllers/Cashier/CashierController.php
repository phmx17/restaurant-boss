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
        <br>';
        // colorzie the table names according to availability
        if($table->status == 'available') {
          $html .= '<span class="badge badge-success">'.$table->name.'</span>';
        } else {
          $html .= '<span class="badge badge-danger">'.$table->name.'</span>';
        }
      $html .= '</button>';
      $html .= '</div>';
    }
    return $html;
  }

  /**
   * display menu on the right column
   */
  public function getMenuByCategory($category_id) 
  {
    $menus = Menu::where('category_id', $category_id)->get();
    
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
          $'.number_format($menu->price, 2).'
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

  // controller for returning sale details to selected table
  public function getSaleDetailsByTable($table_id)
  {
    $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
    $html = '';
    if($sale) {
      $sale_id = $sale->id;
      $html .= $this->getSaleDetails($sale_id);
    } else {
      $html = 'No Sale found for current table';      
    }
    return $html;
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

    $showBtnPayment = true; // flag for changing button from confirm to payment when all sale details are confirmed; default = true

    // loop through sale details and create the table data
    foreach($saleDetails as $saleDetail) {

      $html .= '
      <tr>
        <td>'.$saleDetail->menu_id.'</td>
        <td>'.$saleDetail->menu_name.'</td>
        <td>'.$saleDetail->quantity.'</td>
        <td>'.$saleDetail->menu_price.'</td>
        <td>'.($saleDetail->menu_price * $saleDetail->quantity).'</td>';
        if($saleDetail->status == 'Not Confirmed') { 
          $showBtnPayment = false;  // change in order to not display the payment button (until status has been confirmed show confirm button)
          // make into a delete link - using trash icon and btn-delete-saledetail for targeting in index <script> and data-id
          $html .= '<td><a data-id="'.$saleDetail->id.'" class="btn btn-danger btn-delete-saleDetail"><i class="fas fa-trash-alt"></i></a></td>';
        } else {
          // show check mark in a circle icon
          $html .= '<td><i class="fas fa-check-circle"></i></td>';
        }
      $html .= '</tr>';      
    }
    $html .= '</tbody></table></div>'; 
    
    $sale = Sale::find($sale_id);    
    $html .= '<hr>';
    $html .= '<h3>Total Amount: $ '.number_format($sale->total_price, 2).'</h3>'; // allow precision of 2 digits
    
    // change confirm button to payment button if flag is 'true' otherwise revert to 'confirm' button
    if($showBtnPayment) {
      $html .= '<button data-id="'.$sale_id.'" class="btn btn-success btn-block btn-payment" data-totalAmount="'.$sale->total_price.'" data-toggle="modal" data-target="#exampleModal">Make Payment</button>'; 
    } else {
      $html .= '<button data-id="'.$sale_id.'" class="btn btn-warning btn-block btn-confirm-order">Confirm Order</button>'; 
    }
    // .btn-confirm-order is used for targeting in the index jQ script section
    // data-id is for passing to jQ; access: $(this).data('id');
    return $html;    
  }

  /**
   * change status to confirmed in sale details
   */
  public function confirmOrderStatus(Request $request)
  {
    $sale_id = $request->sale_id;
    $saleDetails = SaleDetail::where('sale_id', $sale_id)->update([ // update with an ass array
      'status' => 'Confirmed'
    ]);
    $html = $this->getSaleDetails($sale_id);  // create sale details markup
    return $html;
  }

  /**
   * delete sale detail of a trashed menu item from sale details
   */
  public function deleteSaleDetail(Request $request)
  {
    $saleDetail_id = $request->saleDetail_id;
    $saleDetail = SaleDetail::find($saleDetail_id);
    $sale_id = $saleDetail->sale_id;

    // remove menu item price according to quantity
    $menu_price = ($saleDetail->menu_price * $saleDetail->quantity);
    $saleDetail->delete();

    // locate the sale and update its total_price and save
    $sale = Sale::find($sale_id);
    $sale->total_price = $sale->total_price - $menu_price; 
    $sale->save();

    // collect updated sale details
    $saleDetails = SaleDetail::where('sale_id', $sale_id)->first();
    if($saleDetail) {
      $html = $this->getSaleDetails($sale_id);  // creates markup for sale details container in view
    } else {
      $html .= 'There are no sale details for this table';
    }
    return $html;  
  }

  /**
   * save payment details to Sale in database
   */
  public function savePayment(Request $request) 
  {
    $saleId = $request->sale_id;
    $receivedAmount = $request->received_amount;
    $paymentType = $request->payment_type;
    // update Sale record in database
    $sale = Sale::find($saleId)->first();    
    $sale->total_received = $receivedAmount;
    $sale->total_change = $receivedAmount - $sale->total_price;
    $sale->payment_type = $paymentType;
    $sale->sale_status = 'paid';
    $sale->save();
    // update table to be available for next guests
    $table = Table::find($sale->table_id);
    $table->status = 'available';
    $table->save();
    return '/cashier';
  }
  }