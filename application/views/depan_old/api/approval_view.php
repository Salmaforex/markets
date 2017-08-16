<?php 
if (   function_exists('logFile')){ logFile('view/member/api','api_view.php','view'); };

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
					<a href="#">Approve Member</a>
					<i class="fa fa-circle"></i>
				</li> 
		</ul>
		<div id='preview'></div>
<table id="tableApproval" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Date</th>
				<th>Full name</th> 
                <th>Username</th>
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
urlAPI="<?=base_url("member/data?type=userApproval");?>";
urlDetail="<?=base_url("member/data");?>";
urlChangeStatus="<?=site_url("member/data?type=update");?>";
</script>	
</div></div>