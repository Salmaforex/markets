<?php 
if (   function_exists('logFile')){ logFile('view/member/api','api_view.php','view'); };
//$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']; 
?>

  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
<div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold">
		  API
		  </span> </div>
          <div class="panel-body">
		  	<div id='preview'></div>
			<table id="tableAPI" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>URL</th>
							<th>Param</th>
							 
							<th>Action</th>
						</tr>
					</thead>
					<tfooter>
						<tr>
							<th>Date</th>
							 <th>URL</th>
							<th>Param</th>
							 
							<th>Action</th>
						</tr>
					</tfooter>
			</table>
		  </div>
</div>
</div></div></div>
<script>
urlBase="<?=site_url();?>";
urlAPI="<?=site_url("member/data?type=api");?>";
urlDetail="<?=site_url("member/data");?>";
</script>

    <!--div style='margin-top:30px;'>
		<ul class="page-breadcrumb breadcrumb">
				<li>
					<?=anchor(site_url('member'),'Home');?>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Api</a>
					<i class="fa fa-circle"></i>
				</li> 
		</ul>
	<a href="<?=site_url("member/listApi/normal");?>" >
		<input type='button' value='API' />
	</a>
	<a href="<?=site_url("member/listApi/deposit");?>" >
		<input type='button' value='Deposit' />
	</a>
	<a href="<?=site_url("member/listApi/widtdrawal");?>" >
		<input type='button' value='Widtdrawal' />
	</a>
	
<hr/>	
	

	
</div></div-->