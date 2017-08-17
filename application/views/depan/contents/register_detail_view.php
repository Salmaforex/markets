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

?>
        </div>
        <div class="panel">
          <div class="panel-heading partition-blue">
            <span class="text-bold">Account Create</span>
          </div>
          <div class="panel-body partition-white">
          <div class="vspace-30"></div>
<?php
if($this->session->flashdata('detail')){
	$this->load->view('depan/inc/register_detail_account_view');
}
?> 
 <hr/>
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