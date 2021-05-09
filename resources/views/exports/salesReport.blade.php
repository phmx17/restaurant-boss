<table>
  <thead><!-- main header in blue -->
    <tr>
      <th>#</th>
      <th>Receipt ID</th>
      <th>Date Time</th>
      <th>Table</th>
      <th>Staff</th>
      <th>Total Amount</th>
    </tr>
  </thead>
  <tbody>
    @php
      $countSale = 1
    @endphp

    @foreach($sales as $sale)
      <tr><!-- show sale for a table -->
   
        <td>{{$countSale++}}</td>
        <td>{{$sale->id}}</td>
        <td>{{date('m/d/Y H:i:s', strtotime($sale->updated_at))}}</td>
        <td>{{$sale->table_name}}</td>
        <td>{{$sale->user_name}}</td>
        <td>{{$sale->total_price}}</td>
      </tr>
  
        <tr><!-- header for sales details -->
          <th></th>
          <th>Menu ID</th>
          <th>Menu</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total Price</th>
        </tr>
      
        @foreach($sale->saleDetails as $saleDetail)
        <tr><!-- sale details -->
          <td ></td>
          <td>{{$saleDetail->menu_id}}</td>
          <td>{{$saleDetail->menu_name}}</td>
          <td>{{$saleDetail->quantity}}</td>
          <td>{{$saleDetail->menu_price}}</td>
          <td>{{$saleDetail->menu_price * $saleDetail->quantity}}</td>
        </tr>
        @endforeach      
      @endforeach
      <tr>
        <td colspan="5">Total Amount From {{$dateStart}} to {{$dateEnd}}</td>
        <td>{{number_format($totalSales, 2)}}</td>
      </tr>
  </tbody>
</table>