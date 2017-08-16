<?php 
/*** menu ***/
?>
<div class="top-header">
  <div class="container">
    <div class="row">
      <div class="social col-md-6">
        <ul class="list-inline">
          <li><a href="<?=ciConfig('fb_link');?>#salma"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
          <li><a href="<?=ciConfig('twitter_link');?>#salma"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
          <li><a href="<?=ciConfig('ig_link');?>#salma"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
          <li><a href="<?=ciConfig('email_link');?>#salma"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
        </ul>
      </div>
      <div class="quicklinks col-md-6">
        <ul class="list-inline text-right">
			<?php if(isset($show_open_live)){?>
          <li><a href="<?=base_url('welcome');?>"><span class="fa fa-user"></span>
			<span class="small">Open Live Account</span></a></li>
			<?php } ?>
          <li><a href="<?=base_url('login');?>"><span class="fa fa-user"></span>
			<span class="small">Login</span></a></li>
          <li><a href="<?=base_url('deposit-form');?>"><span class="fa fa-import"></span>
			<span class="small">Deposit</span></a></li>
          <li><a href="<?=base_url('widtdrawal-form');?>"><span class="fa fa-export"></span>
			<span class="small">Withdrawal</span></a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php /*
<div class="top-header">
	<div class="container">
    	<div class="row">
        	<div class="social col-md-6">
                <ul class="list-inline">
                    <li><a href="https://www.facebook.com/salmaforexbroker"><span class="glyphicon-social facebook"></span></a></li>
                    <li><a href="https://twitter.com/salmaforex"><span class="glyphicon-social twitter"></span></a></li>
                    <li><a href="https://instagram.com/salmaforex/"><span class="glyphicon-social instagram"></span></a></li>
                    <li><a href="http://www.salmaforex.com/contact/"><span class="glyphicon-social e-mail"></span></a></li> 
                </ul>
            </div>
            <div class="quicklinks col-md-6">	
                <ul class="list-inline text-right">
			<?php if(isset($show_open_live)){?>
                    <li><a href="<?=base_url('welcome');?>"><span class="glyphicon glyphicon-user"></span> <span class="small">Open Live Account</span></a></li>
			<?php } ?>
                    <li><a href="<?=base_url('login');?>"><span class="glyphicon glyphicon-user"></span> <span class="small">Login</span></a></li>
                    <li><a href="<?=base_url('deposit-form');?>"><span class="glyphicon glyphicon-import"></span> <span class="small">Deposit</span></a></li>
                    <li><a href="<?=base_url('widtdrawal-form');?>"><span class="glyphicon glyphicon-export"></span> <span class="small">Withdrawal</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
*/