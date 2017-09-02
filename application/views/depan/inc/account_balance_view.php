<?php
//account_balance_view
if(isset($userlogin['balance']['Balance'])){
?>
            <ul class="list-group text-dark panel-shadow">
              <li class="list-group-item active partition-blue"> <span class="text-bold">SalmaForex Ballance</span> </li>
              <li class="list-group-item "> <span class="badge "><?=$userlogin['accountid'];?></span> <i class="fa fa-user-md" aria-hidden="true"></i>&nbsp; <span class="text-bold">Account</span> </li>
              <li class="list-group-item"> <span class="badge ">USD
			  <?=number_format($userlogin['balance']['Balance'],2);?></span> <i class="fa fa-money" aria-hidden="true"></i>&nbsp; <span class="text-bold">Balance</span> </li>
            </ul>
<?php
}
else{
?>
	<ul class="list-group text-dark panel-shadow">
              <li class="list-group-item active partition-blue"> <span class="text-bold">SalmaForex Account</span> </li>
              <li class="list-group-item "> <span class="badge "><?=isset($userlogin['totalAccount'])?$userlogin['totalAccount']:0;?></span> <i class="fa fa-user-md" aria-hidden="true"></i>&nbsp; <span class="text-bold">Total Account</span> </li>
              <li class="list-group-item"> <span class="badge ">On Account Detail </span> <i class="fa fa-money" aria-hidden="true"></i>&nbsp; <span class="text-bold">Balance</span> </li>
            </ul>
<?php 
}