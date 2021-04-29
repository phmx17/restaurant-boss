$(document).ready(function() {
  // hide table by default
  $('#table-detail').hide();
  // show table on button
  $('#btn-show-tables').click(function() {  // click button
    if($('#table-detail').is(":hidden")) {
      $.get("/cashier/getTables", function(data) {  // get data from route
      $('#table-detail').html(data);  // print data to div        
      $('#table-detail').slideDown('fast'); // slide down
      });
    } else {
      $('#table-detail').slideUp('fast'); // slide up
    }
  });
});