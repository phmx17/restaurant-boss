<?php

namespace App\Exports;
use App\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesReportExport implements FromView
{
  // define class vars
  private $dateStart;
  private $dateEnd;
  private $sales;
  private $totalSales;

  // constructor; get Sale data
  public function __construct($dateStart, $dateEnd)
  {
    $dateStart = date('Y-m-d:H:i:s', strtotime($dateStart));  // format for query
    $dateEnd = date('Y-m-d:H:i:s', strtotime($dateEnd));
    $sales = Sale::whereBetween('updated_at', [$dateStart, $dateEnd])->where('sale_status', 'paid')->get();  
    $totalSales = $sales->sum('total_price');
    $this->dateStart = $dateStart;
    $this->dateEnd = $dateEnd;
    $this->sales = $sales;
    $this->totalSales = $totalSales;
  }

  public function view(): View
  {
    // create the excel file with view()
    return view('exports.salesReport', [
      'sales' => $this->sales,
      'totalSales' => $this->totalSales,
      'dateStart' => $this->dateStart,
      'dateEnd' => $this->dateEnd
    ]);
  }
}
