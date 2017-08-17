<?php 
 
?>
  <div class="container">
    <div class="row">
      <div class="main col-md-12 text-white">
        <h3 class="orange nomargin"><strong>Register <?=$statAccount=='agent'?'Partner':'Member';?> Account of SalmaMarkets</strong></h3>
        <h5><strong>Welcome and Join With Us</strong></h5>
        <div class="vspace-30"></div>
      </div>
      <div class="main-left col-md-7">
		<div class="panel panel-white registerpartner">
          <div class="panel-heading partition-blue"> <span class="text-bold">Register Form</span> </div>
          <div class="panel-body" style="padding-right: 30px;">
<?php 
// style='background:rgba(124, 144, 252, 0.72);padding:10px;width:700px;margin-bottom:40px'
		if(isset($register['message'])){ ?>
		      <div class="alert alert-danger">
              <button data-dismiss="alert" class="close"> × </button>
              <?=$register['message'];?>
			  </div>
<?php	}  
?>

            
            <div class="vspace-15"></div>
<?php
/*
            	<!--form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-default">Register</button>
                    </div>
                  </div>
                </form-->				
<?php
*/
			$load_view=$baseFolder.'form_view';
			$this->load->view($load_view); 
?>
            <div class="vspace-15"></div>
          </div>
        </div>
      </div>
    </div>
  </div>