<?php
include "header.php";
include "nav.php";
?>
<div class="col-md">
    <table class="table table-bordered table-striped" id="currencies">
        <thead class="bg-primary">
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Value</th>
                <th>USD Equivalent</th>
                <th>Token Address</th>
                <th>Graphical View</th>
            </tr>
        </thead>
    </table>
</div>
<?php
include "footer.php";
?>
<script>
$(document).ready(function(){
	//alert("load");
   var table = $('#currencies').DataTable({
      'responsive': true,
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'get_currencies.php'
      },
      'columns': [
         { data: 'id' },
         { data: 'name' },
         { data: 'symbol' },
         { data: 'value' },
         { data: 'usd' },
		 { data: 'token_address' },
         { data: 'view' }
      ]
   });
});
  </script>