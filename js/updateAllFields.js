$(document).ready(function(){
  $('#updateAllMiners').click(function(){
    // Loop through each row in the table
    $('table tbody tr').each(function(){
      var row = $(this);
      var minerId = row.find('button[name="update"]').val();
      var minerLabel = row.find('input[name="miner-label"]').val();
      var minerSn = row.find('input[name="miner-sn"]').val();
      var minerModel = row.find('input[name="miner-model"]').val();
      var minerPsuModel = row.find('input[name="miner-psu-model"]').val();
      var minerPsuSn = row.find('input[name="miner-psu-sn"]').val();
      var locationId = row.find('select[name="locationChange"]').val();
      var customerId = row.find('select[name="customerChange"]').val();
      var minerCondition = row.find('input[name="miner-condition"]').val();

      // Send Ajax request to update fields
      $.ajax({
        url: './functions/update_all_miners.php',
        type: 'POST',
        data: {
          minerId: minerId,
          minerLabel: minerLabel,
          minerSn: minerSn,
          minerModel: minerModel,
          minerPsuModel: minerPsuModel,
          minerPsuSn: minerPsuSn,
          locationId: locationId,
          customerId: customerId,
          minerCondition: minerCondition
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
