<?php 
if (  function_exists('logFile')){ logFile('view/member/api','widtdrawal_view.php','view'); };
 
?>

  <div class="container-fluid">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
<div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold">
		  EMAIL
		  </span> </div>
          <div class="panel-body">
			<div id='preview'></div>
			<table id="tableEmail" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>Tujuan</th>
							
							<th>Header</th>
							
						</tr>
					</thead>
					<tfooter>
						<tr>
							<th>Date</th>
							<th>Tujuan</th>
							
							<th>Header</th>
						</tr>
					</tfooter>
			</table>
                        <hr/>
                        <table id="tableEmailLogs" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>status</th>							
							<th>Jumlah</th>
							
						</tr>
					</thead>
					<tfooter>
						<tr>
							<th>Date</th>
							<th>Tujuan</th>
							
							<th>Header</th>
						</tr>
					</tfooter>
			</table>

<script>
urlBase="<?=site_url();?>";
urlAPI="<?=site_url("admin/data?type=email");?>";
urlAPILogs="<?=site_url("admin/data?type=email_logs");?>";
urlData="<?=site_url("admin/data");?>";
</script>	
</div></div>
</div></div></div>