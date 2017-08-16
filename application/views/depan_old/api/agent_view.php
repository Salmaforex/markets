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
					<a href="#">Agent</a>
					<i class="fa fa-circle"></i>
				</li> 
		</ul>
		<div id='preview'></div>
<table id="tableAgent" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Date</th>
				<th>Full name</th> 
                <th>Username</th>
                <th>Email</th>
                <th>Type</th>
                
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
                
                <th>Action</th>
            </tr>
        </tfooter>
</table>	

<script>
urlAPI="<?=base_url("member/data?type=agent");?>";
urlDetail="<?=base_url("member/data");?>";
</script>	
</div></div><?php 
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