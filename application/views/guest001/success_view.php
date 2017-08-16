<?php 
 
?>
<div class="container">
    	<div class="row">
      <div class="main col-md-12 text-white">
        <h3 class="orange nomargin"><strong>Create Live Account Confirmation</strong></h3>
        <h5><strong>Welcome to Salma Market</strong></h5>
        <div class="vspace-30"></div>
      </div>
      <div class="main-left col-md-7">
        <div class="alert alert-block alert-success fade in">
          <h4 class="alert-heading"><i class="fa fa-check"></i> Success!</h4>
          <p> Your Account Are Registered. </p>
            <div class="vspace-15"></div>
					<div class="well">
<?php
if(isset($session['email']['username'])&&isset($session['email']['password']) ){?>
						<dl class="dl-horizontal">
                <dt> Account Trading </dt>
                <dd>
				<?=isset($session['email']['account']['AccountID'])?$session['email']['account']['AccountID']:'';?> </dd>
                <dt> Password Trading </dt>
                <dd><?=isset($session['email']['account']['MasterPassword'])?$session['email']['account']['MasterPassword']:'';?> </dd>
                <dt> Password Investor </dt>
                <dd> <?=isset($session['email']['account']['InvestorPassword'])?$session['email']['account']['InvestorPassword']:'';?></dd>
				<dt> Username </dt>
				<dd> <?=isset($session['email']['username'])?$session['email']['username']:'';?></dd>
                <dt> Password </dt>
				<dd> <?=isset($session['email']['password'])?$session['email']['password']:'';?></dd>
                <dt> Master Code </dt>
				<dd> <?=isset($session['email']['mastercode'])?$session['email']['mastercode']:'';?></dd>
                <dt> Server </dt>
                <dd> SalmaMarket-Live </dd>
              </dl>
<?php 
}
else{
	echo '<H3> Please Check Your Email For Detail</h3>';
	
}
?>
					</div>
        </div>
      </div>
    	</div>
    </div>
