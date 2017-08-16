<?php
	if($this->session->flashdata('detail')){
			$detail=$this->session->flashdata('detail');

	}
//if(defined('LOCAL')){ echo_r($detail);}
$account=$detail['account'];
/*
[account] => Array
        (
            [AccountID] => 2000fake
            [MasterPassword] => xxxxxx
            [InvestorPassword] => zzzzzzz
            [ResponseCode] => 0
        )
*/
?>
            <div class="text-center marbot-30">
              <h3>Create Additional Live Account</h3>
              <p>Since you have existing live accounts please use this form to create your additional live account.</p>
            </div>
            <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 alert alert-block alert-success">
            <h4 class="alert-heading marbot-15"><i class="fa fa-check"></i> Success!</h4>
              <dl class="dl-horizontal">
                <dt> Account Trading </dt>
                <dd><?=$account['AccountID'];?> </dd>
                <dt> Password Trading </dt>
                <dd><?=$account['MasterPassword'];?> </dd>
                <dt> Password Investor </dt>
                <dd><?=$account['InvestorPassword'];?></dd>
                <dt> Server </dt>
                <dd> SalmaMarket-Live </dd>
              </dl>
            </div>
            <div class="col-md-3"></div>
            </div>
