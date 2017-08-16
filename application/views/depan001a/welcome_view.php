<?php
?>

  <div class="container">
    <div class="row">
      <div class="main-left col-md-3">
        <div class="panel dark">
          <div class="row user-profile">
            <div class="col-xs-3"> <img src="images/avatar-1.jpg" alt=""> </div>
            <div class="col-xs-9">
              <p class="text-large">
			  <span class="text-small block">Hello,</span>
			  <?=isset($userlogin['firstname'])?$userlogin['firstname']:'';?>
			  <?=isset($userlogin['lastname'])?$userlogin['lastname']:'';?>
			  </p>
              <div class="btn-group user-options clearfix"> <a class="btn btn-xs btn-transparent-grey dropdown-toggle" data-toggle="dropdown" href="#"> <span class=" text-extra-small"> MT4 31580 </span>&nbsp;<i class="fa fa-caret-down" aria-hidden="true"></i> </a>
                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="drop2">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </div>
              <a class="btn btn-transparent-grey btn-xs" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Account Management &nbsp;<i class="fa fa-cogs" aria-hidden="true"></i> </a> </div>
          </div>
          <div class="panel-body no-padding">
            <div class="collapse" id="collapseExample" aria-expanded="true" style="">
              <div class="list-group no-margin"> <a class="list-group-item no-radius" href="#"> Dapibus ac facilisis in</a> <a class="list-group-item" href="#"> Morbi leo risus</a> <a class="list-group-item" href="#"> Porta ac consectetur ac</a> <a class="list-group-item" href="#"> Vestibulum at eros</a> </div>
            </div>
          </div>
        </div>
        <div class="panel panel-mainmenu no-overflow">
          <div class="panel-heading no-padding partition-blue"> <a class="panel-link-title" href="#"><span class="fa fa-home"></span> <span class="text">Home</span></a> </div>
          <div class="panel-body no-padding">
            <div class="mainmenu drop-nav">
              <ul>
                <li> <a href="#"><span class="fa fa-user"></span><span class="text">Profile</span></a> <span class="toggle fa fa-chevron-down"></span>
                  <ul>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 1</a></li>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 2</a></li>
                  </ul>
                </li>
                <li> <a href="#"><i class="fa fa-briefcase" aria-hidden="true"></i> <span class="text">Deposit</span></a> <span class="toggle fa fa-chevron-down"></span>
                  <ul>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 1</a></li>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 2</a></li>
                  </ul>
                </li>
                <li> <a href="#"><i class="fa fa-exchange" aria-hidden="true"></i> <span class="text">Withdrawal</span></a> <span class="toggle fa fa-chevron-down"></span>
                  <ul>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 1</a></li>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 2</a></li>
                  </ul>
                </li>
                <li> <a href="#"><i class="fa fa-server" aria-hidden="true"></i> <span class="text">Platform</span></a> <span class="toggle fa fa-chevron-down"></span>
                  <ul>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 1</a></li>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 2</a></li>
                  </ul>
                </li>
                <li> <a href="#"><i class="fa fa-shield" aria-hidden="true"></i> <span class="text">Security</span></a> <span class="toggle fa fa-chevron-down"></span>
                  <ul>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 1</a></li>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 2</a></li>
                  </ul>
                </li>
                <li> <a href="#"><i class="fa fa-life-ring" aria-hidden="true"></i> <span class="text">Support</span></a> <span class="toggle fa fa-chevron-down"></span>
                  <ul>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 1</a></li>
                    <li><a href="#"><span class="fa fa-triangle-right"></span> Subnav 2</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <div class="panel-footer partition-blue text-right"><a href="#" class="btn btn-transparent-white btn-xs">Logout <i class="fa fa-power-off" aria-hidden="true"></i> </a></div>
        </div>
      </div>
      <div class="main col-md-9">
        <div class="row">
          <div class="col-md-4">
            <ul class="list-group text-dark panel-shadow">
              <li class="list-group-item active partition-blue"> <span class="text-bold">SalmaForex Ballance</span> </li>
              <li class="list-group-item "> <span class="badge ">1423677</span> <i class="fa fa-user-md" aria-hidden="true"></i>&nbsp; <span class="text-bold">Account</span> </li>
              <li class="list-group-item"> <span class="badge ">IDR 18.000.000</span> <i class="fa fa-money" aria-hidden="true"></i>&nbsp; <span class="text-bold">Balance</span> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="#" class="list-group-item active partition-blue"> Add Deposit <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
              <li class="list-group-item "> <a href="#" class="block text-center"><img style="width: 89px;" src="images/deposit.png"></a> </li>
            </ul>
          </div>
          <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
              <a href="#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
              <li class="list-group-item "> <a href="#" class="block text-center"><img style="width: 89px;" src="images/withdraw.png"></a> </li>
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
          <div class="panel-heading partition-blue"> <span class="text-bold"> Detail</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
              <div class="col-md-6 no-padding">
                <table class="table no-margin" id="">
                  <tbody>
                    <tr class="active">
                      <td>Account (utama)</td>
                      <td class="text-right">
					  <?=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:false;?>
					  </td>
                    </tr>
                    <tr>
                      <td>Name:</td>
                      <td class="text-right">
						<?=isset($userlogin['firstname'])?$userlogin['firstname']:'';?>
						<?=isset($userlogin['lastname'])?$userlogin['lastname']:'';?>
					</td>
                    </tr>
                    <tr class="active">
                      <td>Leverage</td>
                      <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Account Type</td>
                      <td class="text-right">
					  <?=isset($accounts[0]['type'])?$accounts[0]['type']:false;?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td>Account Status</td>
                      <td class="text-right">???</td>
                    </tr>
                    <tr >
                      <td>Phone Number</td>
                      <td class="text-right">???</td>
                    </tr>
                    <tr class="active">
                      <td colspan=2>Address</td>
                    </tr>
                    <tr>
                      <td colspan=2>??</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6 no-padding">
                <table class="table no-margin" id="">
                  <tbody>
                    <tr>
                      <td>Deposit:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr class="active">
                      <td>Free Margin:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr>
                      <td>Total Profit:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr class="active">
                      <td>Total Open Transaction:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr>
                      <td>Total Close Transaction:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr class="active">
                      <td>Total Open Volume Transaction:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr>
                      <td>Total Close Volume Transaction:</td>
                      <td class="text-right">3453467</td>
                    </tr>
                    <tr class="active">
                      <td>Balance</td>
                      <td class="text-right">3453467</td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
				<div>
				<?php
				if(defined('LOCAL')){
				echo_r($userlogin);
				echo_r($accounts);
				}
				?>
				</div>
          </div>
        </div>
      </div>
    </div>
  </div>
