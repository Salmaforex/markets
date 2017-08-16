<?php 
if (   function_exists('logFile')){ logFile('view/member/api','api_view.php','view'); };

?>
  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
<div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold">
		  User Detail
		  </span> </div>
          <div class="panel-body">
		<div id='preview'></div>
<table id="tableUsers" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Date</th>
				<th>Full name</th> 
                <th>Total Akun</th>
                <th>Email</th>
                <th>Type</th>
                <th>Status</th>
                
                <th>Action</th>
            </tr>
        </thead>
		<tfooter>
            <tr>
				<th>Date</th>
				<th>Full name</th> 
                <th>Username</th>
                <th>Email</th>
                <th>Type</th>
                <th>Status</th>
                
                <th>Action</th>
            </tr>
        </tfooter>
</table>	

<script>
urlBase="<?=base_url();?>";
urlAPI="<?=base_url("member/data?type=user");?>";
urlDetail="<?=base_url("member/data");?>";
urlChangeStatus="<?=site_url("member/data?type=update");?>";
</script>	
</div></div>
</div></div></div>