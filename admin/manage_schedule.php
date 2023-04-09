<div class="container">

Managing Time Availability : <br/> <span style="font-weight:bold"><?php  echo date('F j,Y',strtotime($_GET['schedule']))?></span>
<button class="btn btn-primary btn-sm px-4 mb-3" id="btnaddtime" style="float:right">Add <i class="fas fa-plus-circle"></i></button>
<div class="table-responsive">
<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Time Start</th>
      <th scope="col">Time End</th>
      <th scope="col">Alloted Slots</th>
      <th scope="col">Occupied Slots</th>
      <th scope="col">Vacant Slots</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id="tabledata">
  </tbody>
</table>
</div>

</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
 integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" 
 crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    
    function fetchdata(date){
        start_loader();
        $.ajax({
  url: '../classes/Adds_on.php',
  method: 'GET',
  data: {fetchdata : date },
  success: function(response) {
    end_loader()
    $('#tabledata').html(response);
  },
  error: function(jqXHR, textStatus, errorThrown) {
    // Handle the error
    console.error(textStatus + ': ' + errorThrown);
  }
});
    }

fetchdata("<?php echo $_GET['schedule']?>");
 $('#btnaddtime').click(function(){
 
  $.ajax({
  url: '../classes/Adds_on.php',
  method: 'GET',
  data: {addtime : "<?php echo $_GET['schedule']?>" },
  success: function(response) {
  
    fetchdata("<?php echo $_GET['schedule']?>");
  },
  error: function(jqXHR, textStatus, errorThrown) {
    // Handle the error
    console.error(textStatus + ': ' + errorThrown);
  }
});
 })
    
</script>