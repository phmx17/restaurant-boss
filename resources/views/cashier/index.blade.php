@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row" id="table-detail"></div>
  <div class="row justify-content-center">
    <div class="col-md-5">
      <button class="btn btn-primary btn-block" id="btn-show-tables">View All Tables</button>
      <div id="selected-table"></div>
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
        console.log(data)
        $('#list-menu').hide();
        $('#list-menu').html(data);
        $('#list-menu').fadeIn('slow');
      });
    });
    // detect button table on click. 
    $('#table-detail').on('click', '.btn-table', function() {
       const selectedTableId = $(this).data('id'); // $this refers to '.btn-table'
       const selectedTableName = $(this).data('name');
       $('#selected-table').html(`<br><h3>Table: ${selectedTableName}</h3><hr>`);
      
    } )
  });
</script>
@endsection 
