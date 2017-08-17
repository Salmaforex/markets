<?php
//register_account.php
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
$email=isset($userlogin['email'])?$userlogin['email']:'';
$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';

?>
  <div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
        <div class="page-heading">
<?php 
	if($this->session->flashdata('forgot')){
			$forgot=$this->session->flashdata('forgot');
			logCreate('session forgot valid','info');
	}
		if(isset($forgot['status'])&&$forgot['status']==false){ ?>
							<div class="alert alert-block alert-danger fade in">
								<button type="button" class="close" data-dismiss="alert"></button>
								<h4 class="alert-heading">Warning!</h4>
								<p><?=$forgot['message'];?></p>								
							</div>
<?php	} 
		if(isset($forgot['status'])&&$forgot['status']==true){ ?>
							<div class="alert alert-block alert-info fade in">
								<button type="button" class="close" data-dismiss="alert"></button>
								<h4 class="alert-heading">Warning!</h4>
								<p><?=$forgot['message'];?></p>								
							</div>
<?php	}  
?>
        </div>
        <div class="panel">
          <div class="panel-heading partition-blue">
            <span class="text-bold">Account Create</span>
          </div>
          <div class="panel-body partition-white">
          <div class="vspace-30"></div>
<?php $this->load->view('depan/inc/form_register_account_view');?>
          </div>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function(e) {
    $('#validate').formValidation({
        framework: 'bootstrap',
        err: {
            container: 'tooltip'
        },
        fields: {
          stp: {
						validators: {
							notEmpty: {
								message: 'Type your username.'
							}
						}
          },
					code: {
						validators: {
							notEmpty: {
								message: 'Type your mastercode.'
							}
						}
					}
        }
    });
});
</script>