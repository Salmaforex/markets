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
		 Account
		  </span> </div>
          <div class="panel-body">
		<div id='preview'></div>
<table id="table_account" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Date</th>
                <th>Name</th>
                <th>Email</th>
                <th>ACC ID</th>
                <th>Agent</th>
                <th>Type</th>
                
                <th>Action</th>
            </tr>
        </thead>
		<tfooter>
            <tr>
				<th>Date</th>
                <th>Name</th>
                <th>Email</th>
                <th>ACC ID</th>
                <th>Agent</th>
                <th>Type</th>
                
                <th>Action</th>
            </tr>
        </tfooter>
</table>	

<script>
urlBase="<?=base_url();?>";
urlAPI="<?=base_url("admin/data?type=account");?>";
urlDetail="<?=base_url("admin/data");?>";
</script>	
</div></div>
</div></div></div><?php 
/*
$sql="select count(a.id) c from mujur_account a, mujur_register r where a.reg_id=r.reg_id and r.reg_agent !=''  ";
$data=dbFetchOne($sql);
$n=isset($_GET['p'])?$_GET['p']:0;
$max=113;
$total=$data['c'];
echo 'total:'.$data['c'];
$sql="select a.id, a.reg_id, r.reg_agent from mujur_account a, mujur_register r where a.reg_id=r.reg_id and r.reg_agent !='' 
limit $n,$max
 ";
					$res=dbFetch($sql);
					foreach($res as $row){
						$sql="update mujur_account set agent='$row[reg_agent]' where reg_id='$row[reg_id]'";
						//echo "$sql<br/>";
						dbQuery($sql);
					}

if($n < $total){
$n+=$max;
$url=current_url()."?p=$n";
}
else{
	$url=site_url('member/listApi/done')."?p=$n";
}


echo '<script>window.location.href ="'.$url.'";</script>';
*/