<?php
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';//$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
	
//	
	$detail = $userlogin;//$detail1=$detail['detail'];

	$allow=false;
/*
	if(isset($detail1['firstname'])&&$detail1['firstname']!=''){
		$allow=1;
	}
*/
	$this->users_model->addNullDocument($detail['email']);
	$document=$this->users_model->document($detail['email']);

?>
  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
        <div class="row">
          <div class="col-md-4">
	<?php
	$this->load->view('depan/inc/account_balance_view');
	?>

          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=base_url('deposit-form');?>#" class="list-group-item active partition-blue"> Add Deposit <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
              <li class="list-group-item "> <a href="#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/deposit.png"></a> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="<?=base_url('withdraw-form');?>#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
              <li class="list-group-item "> <a href="#" class="block text-center"><img style="width: 89px;" 
			  src="<?=base_url('media');?>/images/withdraw.png"></a> </li>
            </ul>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Welcome to secure area</strong></h3>
            <p>Since you have existing live accounts please use this form to create your additional live account.</p>
          </div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> Summary</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	//$this->load->view($load_view);
	
	//$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
	//$urlAffiliation=base_url("register/{$rand_url}");
?>
			<div class="main col-md-9">
				<div class="page-heading">
					<h3><strong>Upload Document</strong></h3>
				</div>
				<div class="panel">
				  <div class="panel-heading partition-blue"> <span class="text-bold">Uploaded Document</span> </div>
				  <div class="panel-body no-padding partition-white">
					<div class="row no-margin">
					  <div class="col-md-12 no-padding">
<?php 
if(defined('LOCAL')){
	echo_r($userlogin);
	echo_r($document);
}?>
						  <table class="table table-bordered table-striped no-margin editable">
							<thead>
							<tr>
								<th>Identity Type</th>
								<th>Additional Info</th>
								<th>Status</th>
							  </tr>
							</thead>
							<tbody>
							  <tr>
								<td>Goverment ID</td>
								<td><?php 
					if(isset($document['udoc_upload'])&&$document['udoc_status']>=0){ 
						$url=site_url('member/show_upload/'.$document['id']);
						echo anchor_popup($url, 'view Documents'); 
					}?>
					</td>
								<td>
<?php
	if(isset($document['udoc_status'])){
		if($document['udoc_status']==0){
			echo "Waiting";
		}
		elseif($document['udoc_status']==1){
			echo "Approve";
		}
		else{
			echo "-";
		}
	}
	else{
		echo '-';
	}
					
?>
								</td>
							  </tr>
							  <tr>
								<td>Profile Picture</td>
								<td><?php
					if(isset($document['profile_pic'])){ 
						$url=site_url('member/show_profile/'.$document['id']);
						echo anchor_popup($url, 'view Documents'); 
					}?></td>
								<td><?= (isset($document['profile_pic'])&&$document['profile_pic']!='')?"Uploaded":"Not Uploaded";?></td>
							  </tr>
							</tbody>
						  </table>
					  </div>
					</div>
				  </div>
				</div>
				<div class="panel panel-white">
				  <div class="panel-heading partition-blue"> <span class="text-bold">Upload Document</span> </div>
				  <div class="panel-body partition-white">
				  <div class="vspace-15"></div>
				  <?php callback_submit(); 
				  //echo form_open_multipart();
				  ?>
				  <form class="form-horizontal" />
					<div class="form-group">
					  <label class="col-sm-3 control-label no-padding padding-horizontal-10" for="form-field-7">
						Passport or <br /> other goverment ID
					  </label>
					  <div class="col-sm-6">
						<div class="fileupload fileupload-new" data-provides="fileupload">
						  <div class="input-group">
							<div class="form-control uneditable-input">
							  <i class="fa fa-file fileupload-exists"></i>
							  <span class="fileupload-preview"></span>
							</div>
							<div class="input-group-btn">
							  <div class="btn btn-light-grey btn-file">
								<span class="fileupload-new"><i class="fa fa-folder-open-o"></i> Select file</span>
								<span class="fileupload-exists"><i class="fa fa-folder-open-o"></i> Change</span>
								<input type="file" class="file-input" name="doc" />
							  </div>
							  <a href="#" class="btn btn-light-grey fileupload-exists" data-dismiss="fileupload">
								<i class="fa fa-times"></i> Remove
							  </a>
							</div>
						  </div>
						</div>
						<p>max Size: 500kb</p>
					  </div>

					</div>
					<div class="form-group">
					  <label class="col-sm-3 control-label" for="form-field-7">
						Profile Picture
					  </label>
					  <div class="col-sm-6">
						<div class="fileupload fileupload-new" data-provides="fileupload">
						  <div class="input-group">
							<div class="form-control uneditable-input">
							  <i class="fa fa-file fileupload-exists"></i>
							  <span class="fileupload-preview"></span>
							</div>
							<div class="input-group-btn">
							  <div class="btn btn-light-grey btn-file">
								<span class="fileupload-new"><i class="fa fa-folder-open-o"></i> Select file</span>
								<span class="fileupload-exists"><i class="fa fa-folder-open-o"></i> Change</span>
								<input type="file" class="file-input" name="profile_pic" />
							  </div>
							  <a href="#" class="btn btn-light-grey fileupload-exists" data-dismiss="fileupload">
								<i class="fa fa-times"></i> Remove
							  </a>
							</div>
						  </div>
						</div>
						<p>max Size: 500kb</p>
					  </div>

					</div>
					<div class="form-group">
					  <div class="col-xs-3 col-xs-push-3">
					  <button class="btn btn-primary" type="submit">Upload</button>
					  </div>
					</div>
					<input type='hidden' name='rand' value='<?=dbId('id',22222,3);?>' />
					</form>
				  </div>
				</div>


			</div>
<?php
/*
			<div class="main col-md-8">
				<h3 class="orange nomargin"><strong>Welcome to the Secure Area of SalmaForex</strong></h3>
                <p>Dear <?=$name?>&nbsp;<?=isset($userlogin['detail']['lastname'])?$userlogin['detail']['lastname']:'';?>,<br/>
				Your are now logged-in the Secure Area. Here you can view all the Information from your accounts. You can also Update Your Profile before deposit and withdrawn and many more. </p>
                <div class="vspace-30"></div>
				
				<h2>Upload Document</h2>				
<?php
	callback_submit();
	echo form_open_multipart();
	$detail = $userlogin;//$detail1=$detail['detail'];

	$allow=false;

	if(isset($detail1['firstname'])&&$detail1['firstname']!=''){
		$allow=1;
	}

	$this->users_model->addNullDocument($detail['email']);
	$document=$this->users_model->document($detail['email']);
	if(defined('LOCAL')){
	echo_r($userlogin);
	echo_r($document);
	}
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
*/ ?>


			</div>
		</div>
	</div>
	
</div>
</div></div></div>