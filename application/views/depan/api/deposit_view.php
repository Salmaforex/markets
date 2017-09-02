<?php 
if (  function_exists('logFile')){ logFile('view/member/api','deposit_view.php','view'); };
 
?>

  <div class="container-fluid">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
<div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold">
		  DEPOSIT
		  </span> </div>
          <div class="panel-body">
			<div id='preview'></div>
			<table id="tableDeposit" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Date</th> 
							<th>Account ID</th>
							<th>Username/Email</th>
							<th>Name</th>
							<th>Deposit</th>
							<th>Detail</th>
							<th>Status</th>				
							<th>Action</th>
						</tr>
					</thead>
					<tfooter>
						<tr>
							<th>Date</th> 
							<th>Account ID</th>
							<th>Username/Email</th>
							<th>Name</th>
							<th>Deposit</th>
							<th>Detail</th>
							<th>Status</th>				
							<th>Action</th>
						</tr>
					</tfooter>
			</table>	

<script>
urlBase="<?=base_url();?>";
urlAPI="<?=base_url("member/data?type=deposit");?>";
urlData="<?=base_url("member/data");?>";
</script>	
</div></div>
</div></div></div>