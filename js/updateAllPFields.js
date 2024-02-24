
$(document).ready(function(){
  $('#updateAllPsus').click(function(){
    // Loop through each row in the table
    $('table tbody tr').each(function(){
      var row = $(this);
      var psuId= row.find('button[name="update"]').val();
      var psuSn = row.find('input[name="psu-sn"]').val();
      var psuModel = row.find('input[name="psu-model"]').val();
      var locationId = row.find('select[name="locationChange"]').val();
      var customerId = row.find('select[name="customerChange"]').val();
      var psuCondition = row.find('input[name="psu-condition"]').val();
      var psuTicket = row.find('input[name="psu-ticket"]').val();

      // Send Ajax request to update fields
      $.ajax({
        url: './functions/update_all_psus.php',
        type: 'POST',
        data: {
          psuId: psuId,
          psuSn: psuSn,
          psuModel: psuModel,
          locationId: locationId,
          customerId: customerId,
          psuCondition: psuCondition,
          psuTicket: psuTicket
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
