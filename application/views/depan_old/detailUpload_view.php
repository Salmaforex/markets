<?php
$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']; 
?>
	<div class="container">
    	<div class="row">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	$this->load->view($load_view);
	
	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
	$urlAffiliation=base_url("register/{$rand_url}");
?>		
			<div class="main col-md-8"> 
				<h3 class="orange nomargin"><strong>Welcome to the Secure Area of SalmaForex</strong></h3>
                <p>Dear <?=isset($userlogin['detail']['firstname'])?$userlogin['detail']['firstname']:'';?>&nbsp;<?=isset($userlogin['detail']['lastname'])?$userlogin['detail']['lastname']:'';?>,<br/>
				Your are now logged-in the Secure Area. Here you can view all the Information from your accounts. You can also Update Your Profile before deposit and withdrawn and many more. </p>
                <div class="vspace-30"></div>
				
				<h2>Upload Document</h2>				
<?php
	callback_submit();
	echo form_open_multipart();
	$detail1=$detail['detail'];

	$allow=false;
	if(isset($detail1['firstname'])&&$detail1['firstname']!=''){
		$allow=1;
	}
	$document=$this->account->document($detail['id']);
	//echo '<pre>'.print_r($document,1).'</pre>';
?>
			<table class='table-striped table' border="0">
			<tr>
				<td>Upload Dokumen (max 500kb)</td><td>:</td>
				<td>
				<?php 
					$params=array(
						'name'=>'doc',
						'id'=>'doc_upload'
					);
					echo form_upload($params);
					if(isset($document['upload'])){ 
						$url=site_url('member/show_upload/'.$detail['id']);
						echo anchor_popup($url, 'Lihat dokumen'); 
					}
				?>
				</td>
			</tr>
				<?=bsButton('Update');?>
			</table>
			<input type='hidden' name='rand' value='<?=dbId('id',22222,3);?>' />
			</form> 
				
			</div>
		</div>
	</div>