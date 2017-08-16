<?php
//history_view.php
?>
	<div class="container">
    	<div class="row">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	$this->load->view($load_view);
	
	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
	$urlAffiliation=base_url("register/{$rand_url}");
//$user_id=2325;
$user_id=$userlogin['id'];
$data=$this->forex->flowMember($user_id);
if($data['count']==0){?>
<!--
<?php echo '<pre>'.print_r($_POST,1).'</pre>'; ?>
-->
			<div class="main col-md-8"> 
<h3>Tidak ada Catatan Kegiatan</h3>

			</div>
<?php
}
else{?>		
			<div class="main col-md-8">
<hr/>	
	<div id='preview'></div>
				<table id="history-table" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Date</th>
								<th>ID</th>
								<th>Username</th>
								<th>Name</th>
								<th>Tipe</th>
								<th>Transaksi</th>
								<th>Rate</th>
								<th>Total</th>				
								<th>Status</th>
							</tr>
						</thead>
						<tfooter>
							<tr>
								<th>Date</th>
								<th>ID</th>
								<th>Username</th>
								<th>Name</th>
								<th>Tipe</th>
								<th>Transaksi</th>
								<th>Rate</th>
								<th>Total</th>				
								<th>Status</th>
							</tr>
						</tfooter>
				</table>
	</div>
<script>
urlAPI="<?=base_url("member/data?type=history");?>";
urlData="<?=base_url("member/data");?>";
</script>
<?php
}
?>
</div></div>
<?php
//echo '<pre>';print_r($data );echo '</pre>';