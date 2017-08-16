<?php 
if (  function_exists('logFile')){ logFile('view/member/api','widtdrawal_view.php','view'); };

?>
<div class='container'>
    <div style='margin-top:30px;'>
		<ul class="page-breadcrumb breadcrumb">
				<li>
					<?=anchor(base_url('member'),'Home');?>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<?=anchor(base_url('member/listApi'),'API');?>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Widtdrawal</a>
					<i class="fa fa-circle"></i>
				</li> 
		</ul>
	<a href="<?=base_url("member/listApi/normal");?>" >
		<input type='button' value='API' />
	</a>
	<a href="<?=base_url("member/listApi/deposit");?>" >
		<input type='button' value='Deposit' />
	</a>
	<a href="<?=base_url("member/listApi/widtdrawal");?>" >
		<input type='button' value='Widtdrawal' />
	</a>
	
<hr/>	
	<div id='preview'></div>
<table id="tableWidtdrawal" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Date</th>
				<th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Deposit</th>
                <th>Total</th>
                <th>Status</th>				
                <th>Action</th>
            </tr>
        </thead>
		<tfooter>
            <tr>
				<th>Date</th>
				<th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Deposit</th>
                <th>Total</th>
                <th>Status</th>				
                <th>Action</th>
            </tr>
        </tfooter>
</table>	

<script>
urlAPI="<?=base_url("member/data?type=widtdrawal");?>";
urlData="<?=base_url("member/data");?>";
</script>	
</div></div>