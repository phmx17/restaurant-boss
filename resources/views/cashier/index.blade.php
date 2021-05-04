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

<!-- Bootstrap Modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Make Payment</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- modal body -->
      <div class="modal-body">
        <h3 class="totalAmount"></h3><!-- container -->
        <div class="input-group mb-3">
          <div class="input-group-prepend"><span class="input-group-text">Received: $</span></div>
          <input type="number" id="received-amount" class="form-control">
        </div> 
        <h3 class="changeAmount"></h3><!-- container -->
        <div class="form-group">
          <label for="payment">Payment Type</label>
          <select class="form-control" id="payment-type">
            <option value="cash">Cash</option>
            <option value="credit">Credit Card</option>
          </select>
        </div>
      </div>       
      <!-- modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-save-payment" disabled>Save Payment</button>
      </div>
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

    // Global vars
    let selectedTableId = ''
    let selectedTableName = ''
    let saleId = ''

    // detect button table on click. 
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
         type: 'POST',
         data: {
          '_token': $('meta[name="csrf-token"]').attr('content'),
          'sale_id': saleId,
         },
         url: '/cashier/confirmOrderStatus',
         success: (data) => {
          $('#order-details').html(data);  // html is returned from controller
          }
       })      
    })    

    // delete sale details of menu item upon click of trash icon
      $('#order-details').on('click', '.btn-delete-saleDetail', function() {        
        const saleDetailId = $(this).data('id');  // .data() is data from an <a> tag <a data-id="'.$value.'"></a>
        $.ajax({
          type: 'POST',
          data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'saleDetail_id': saleDetailId
          },
          url: '/cashier/deleteSaleDetail',
          success: (data) => {
            $('#order-details').html(data);  // html is returned from controller
          }
        })  
      })

      // user clicks on 'Make Payment' button (not in modal!)
      $('#order-details').on('click', '.btn-payment', function(){
        // const totalAmount = $(this).data('totalAmount');
        const totalAmount = $(this).attr('data-totalAmount')
        $('.totalAmount').html(`Total Amount: ${totalAmount}`)  // push into <h3> tag of modal payment window
        // clear out total and received fields
        $('#received-amount').val('')
        $('.changeAmount').html('')
        // assign global saleId to be used later
        saleId = $(this).data('id')

      })

      // calculate change
      $('#received-amount').keyup(function() {
        const totalAmount = $('.btn-payment').attr('data-totalAmount') // get totalAmount from the button above using attr()
        const receivedAmount = $(this).val()
        let changeAmount = receivedAmount - totalAmount
        changeAmount = changeAmount.toFixed(2)  // make sure to only display 2 decimal points
        $('.changeAmount').html(`Total Change: ${changeAmount}`)   
        
        // enable modal payment button upon cash received
        if(changeAmount >= 0) {
          $('.btn-save-payment').prop('disabled', false);
        } else {
          $('.btn-save-payment').prop('disabled', true);

        }
      })

      // save payment in modal
      $('.btn-save-payment').click(function() {
        const receivedAmount = $('#received-amount').val();
        const paymentType = $('#payment-type').val();
        // const saleId = $('.btn-payment').data('id') // this will not work!!
        const sale_id = saleId  // assign to local from global which is activated on clicking 'Make Payment' button
        // use ajax to send payment details
        $.ajax({
          type: 'POST',
          data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'sale_id': sale_id,
            'received_amount': receivedAmount,
            'payment_type': paymentType
          },
          url: '/cashier/savePayment',
          success: (data) => {
            window.location.href= data;
          }
        })
      })

 




  }); // $(document).ready
</script>
@endsection 
