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
          <p> <?=$respon['message'];?>. </p>
            <div class="vspace-15"></div>
					<div class="well">

						<dl class="dl-horizontal">

				<dt> Username </dt>
				<dd> <?=isset($raw['user']['email'])?$raw['user']['email']:'';?></dd>
                <dt> Password </dt>
				<dd> <?=isset($raw['masterpassword'])?$raw['masterpassword']:'';?></dd>
                <dt> Master Code </dt>
				<dd> <?=isset($raw['mastercode'])?$raw['mastercode']:'';?></dd>
                <dt> Server </dt>
                <dd> SalmaMarket-Live </dd>
              </dl>
			  <?php ?>
					</div>
        </div>
      </div>
    	</div>
    </div>
