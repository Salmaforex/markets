<div class="container">
  <div class="container">
    <div class="row">
      <div class="main col-md-12 text-white">
        <h3 class="orange nomargin"><strong>Recover User Account of SalmaForex</strong></h3>
        <h5><strong>Please Enter Your Email</strong></h5>
        <div class="vspace-30"></div>
      </div>
      <div class="main-left col-md-7">
        <div class="panel panel-white registerpartner">
          <div class="panel-heading partition-blue"> <span class="text-bold">Recover Password</span> </div>
          <div class="panel-body" style="padding-right: 30px;">
<?php 
if($this->session->flashdata('forgot')){
	$forgot=$this->session->flashdata('forgot');
//	echo_r($forgot);
//logCreate('session forgot valid','info');
	if($forgot['status']){?>
	            <div class="alert alert-success">
              <button data-dismiss="alert" class="close"> × </button>
              <strong>Well done! <?=$forgot['message'];?> .</strong> 
			 </div>
	<?php
	}
	else{?>
            <div class="alert alert-danger">
              <button data-dismiss="alert" class="close"> × </button>
              <strong><?=$forgot['message'];?> </strong>  
			</div>	
	<?php
	}
	
}
/*


*/
?>
            <div class="vspace-15"></div>
            <form class="form-horizontal" action="<?=current_url();?>" method="POST">
              
              <div class="form-group">
                <label for="country" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="email" placeholder="Your registered email address" name='email' />
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Recover</button>
                </div>
              </div>
            </form>
            <div class="vspace-15"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
	if($this->session->flashdata('forgot')){
			$forgot=$this->session->flashdata('forgot');
			logCreate('session forgot valid','info');
	}
/*
    	<div class="row">
        	<div class="main col-md-12">
            	<h3 class="orange nomargin"><strong>Register to the Secure Area of SalmaForex</strong></h3>
                <p>Welcome and Join With Us</p>
                <div class="vspace-30"></div>
            </div>
    		<div class="main-left col-md-7">
<?php 
			$load_view=$baseFolder.'formForgot_view';
			$this->load->view($load_view); 
?>
			<div class="vspace-30"></div>
            </div>
            <div class="col-md-1 col-sm-2"></div>
            <!--div class="col-md-4 col-sm-10">
            	<div class="row">
<?php 
			$load_view=$baseFolder.'inc/support_view';
			//$this->load->view($load_view); 
?>
                </div>
            </div-->
    	</div>
*/
?>
</div>