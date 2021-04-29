@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row" id="table-detail"></div>
  <div class="row justify-content-center">
    <div class="col-md-5">
      <button class="btn btn-primary btn-block" id="btn-show-tables">View All Tables</button>
    </div>
    <div class="col-md-7">      
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
  });
</script>
@endsection
