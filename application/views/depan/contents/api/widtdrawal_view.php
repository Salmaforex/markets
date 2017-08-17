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
		  Withdrawal
		  </span> </div>
          <div class="panel-body">
			<div id='preview'></div>
			<table id="tableWidtdrawal" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                        <th>Date</th>
                                        <th>No Akun</th>
                                        <th>Username/Email</th>
                                        <th>Name</th>
                                        <th>Total</th>
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
                                        <th>Total</th>
                                        <th>Detail</th>
                                        <th>Status</th>				
                                        <th>Action</th>
                                </tr>
                            </tfooter>
			</table>	

<script>
urlBase="<?=site_url();?>";
urlAPI="<?=site_url("member/data?type=widtdrawal");?>";
urlData="<?=site_url("member/data");?>";
</script>	
</div></div>
</div></div></div>