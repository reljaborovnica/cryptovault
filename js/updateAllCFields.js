$(document).ready(function(){
  $('#updateAllCbs').click(function(){
    // Loop through each row in the table
    $('table tbody tr').each(function(){
      var row = $(this);
      var cbId= row.find('button[name="update"]').val();
      var cbSn = row.find('input[name="cb-sn"]').val();
      var cbModel = row.find('input[name="cb-model"]').val();
      var locationId = row.find('select[name="locationChange"]').val();
      var customerId = row.find('select[name="customerChange"]').val();
      var cbCondition = row.find('input[name="cb-condition"]').val();
      var cbTicket = row.find('input[name="cb-ticket"]').val();

      // Send Ajax request to update fields
      $.ajax({
        url: './functions/update_all_cbs.php',
        type: 'POST',
        data: {
          cbId: cbId,
          cbSn: cbSn,
          cbModel: cbModel,
          locationId: locationId,
          customerId: customerId,
          cbCondition: cbCondition,
          cbTicket: cbTicket
        },
        success: function(response){
          // Handle success response
          console.log(response);
        },
        error: function(xhr, status, error){
          // Handle error
          console.error(error);
        }
      });
    });
  });
});

