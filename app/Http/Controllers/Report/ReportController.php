<?php

namespace App\Http\Controllers\Report;

use App\Sale;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
  public function index()
  {
    return view('report.index');
  }

  /**
   * show the report page
   */
  public function show(Request $request)
  {
    $request->validate([
      'dateStart' => 'required',
      'dateEnd' => 'required'
    ]);
    
    $dateStart_Ymd = date('Y-m-d H:i:s', strtotime($request->dateStart . '00:00:00'));  // format for query
    $dateEnd_Ymd = date('Y-m-d H:i:s', strtotime($request->dateEnd . '23:59:59'));
    $dateStart_mdY = date("m/d/Y H:i:s", strtotime($request->dateStart.' 00:00:00'));  // format for view
    $dateEnd_mdY = date('m/d/Y H:i:s', strtotime($request->dateEnd. '23:59:59'));
    $sales = Sale::whereBetween('updated_at', [$dateStart_Ymd, $dateEnd_Ymd])->where('sale_status', 'paid');  

    return view('report.showReport')
    ->with('dateStart', $dateStart_mdY)
    ->with('dateEnd', $dateEnd_mdY)
    ->with('totalSale', $sales->sum('total_price'))
    ->with('sales', $sales->paginate(5));
  }
  /**
   * export report to excel; 
   */
  public function export(Request $request)
  {
    // create new instance; the class then calls a view() to populate the xlsx sheet
    return Excel::download(new SalesReportExport($request->dateStart, $request->dateEnd), 'salesReport.xlsx'); // name the downloaded file
  }
}
