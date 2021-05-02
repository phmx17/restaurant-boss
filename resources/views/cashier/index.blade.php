@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row" id="table-detail"></div>
  <div class="row justify-content-center">
    <div class="col-md-5">
      <button class="btn btn-primary btn-block" id="btn-show-tables">View All Tables</button>
      <div id="selected-table"></div><!-- container for table details -->
      <div id="order-details"></div><!-- container for order details -->
    </div>
    <div class="col-md-7">  
      <nav>
        <!-- create tablist -->
        <div class="nav nav-tabs" id="nav-tab" role="tablist"><!-- role is for Aria -->
          <!-- create nav tabs -->
          @foreach($categories as $category)
            <a class="nav-item nav-link" data-id="{{$category->id}}" data-toggle="tab">
              {{$category->name}}
            </a>
          @endforeach
        </div>
      </nav>    
      <div id="list-menu" class="row mt-2"></div><!-- container for list-menu -->
    </div>       
  </div>
</div>
<script>
  $(document).ready(function() {
    // hide table by default
    $('#table-detail').hide();
    // show table on button
    $('#btn-show-tables').click(function() {  // click button
      if($('#table-detail').is(":hidden")) {
        $.get("/cashier/getTables", function(data) {  // get data from route
        $('#table-detail').html(data);  // print data to div
        $('#table-detail').slideDown('medium'); // slide menu down
        $('#btn-show-tables').html('Hide Tables').removeClass('btn-primary').addClass('btn-danger');
      });
    } else {
      $('#table-detail').slideUp('medium'); // slide up
      $('#btn-show-tables').html('Show All Tables').removeClass('btn-danger').addClass('btn-primary');      
      }
    });

    // load menus by category
    $('.nav-link').click(function() {
      $.get(`/cashier/getMenuByCategory/${$(this).data('id')}`, function(data) {
        $('#list-menu').hide();
        $('#list-menu').html(data); // push data (menu id and image) into list-menu container (24)
        $('#list-menu').fadeIn('slow');
      });
    });

    // detect button table on click. 
    let selectedTableId = ''
    let selectedTableName = ''
    let sale_id = ''
    $('#table-detail').on('click', '.btn-table', function() {
       selectedTableId = $(this).data('id'); // $this refers to '.btn-table'
       selectedTableName = $(this).data('name');
       $('#selected-table').html(`<br><h3>Table: ${selectedTableName}</h3><hr>`); // populate #selected-table container
       $.get(`/cashier/getSaleDetailsByTable/${selectedTableId}`, (data) => {
        $('#order-details').html(data)  // push data into container
       })      
    })

    // click on menu pic to display in table detail
    $('#list-menu').on('click', '.btn-menu', function() { // .btn-menu was created in controller @getMenuByCategory
      // do not allow selection of menu item before a table is selected
      if(selectedTableId == '') {
        alert('you need to select a table first')
      } else {
        const menu_id = $(this).data('id');
        // make ajax request
        $.ajax({
          type: 'POST',
          data: {
            '_token': $('meta[name="csrf-token"').attr('content'),
            'menu_id': menu_id,
            'table_id': selectedTableId,
            'table_name': selectedTableName,
            'quantity': 1
          },
          url: '/cashier/orderFood',
          success: function(data) {
            $('#order-details').html(data)  // push data into container
          }
        })
      }
    })
    // confirming order via target of button; class markup was created in controller $html by getSaleDetails()
    $('#order-details').on('click', '.btn-confirm-order', function() {  // do not use fat arrow '=>' since 'this' needs access
       const saleId = $(this).data('id') // defined in controller markup: data-id="'.$sale_id.'"
       $.ajax({
         type: "POST",
         data: {
          '_token': $('meta[name="csrf-token"').attr('content'),
          'sale_id': saleId,
         },
         url: '/cashier/confirmOrderStatus',
         success: (data) => {
          $('#order-details').html(data);  // html is returned from controller
         }
       })

    })

 




  }); // $(document).ready
</script>
@endsection 
