<?php

$email = $userlogin['email'];
$reg_id = dbId();
//$userlogin['firstname']." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
if(!isset($active_controller)){
	$active_controller='member';
}
$listAcc=array();
if(isset($accounts)){
	$debug=array();
	foreach($accounts as $row){
		$debug[]=print_r($row,1);
		$type=isset($row['type'])?strtoupper($row['type']):false;
		if(isset($row['accountid'])){
			$listAcc[$type][]=array($row['accountid'], $row['agent']);
		}
	}
}
$account_id=$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';


//===========================
$raw= _localApi( 'users','detail',array($email));
$detail = $raw['data'];
$name=isset($detail['name'])?$detail['name']:'';
//echo_r($detail);
if(isset($detail['document']['profile_pic'])){
    $profile_pic=$detail['document']['profile_pic']!=''?site_url('member/show_profile/'.$detail['document']['id'].'/100'):false;
}
else{
    $profile_pic='';
}
?>
      <div class="main-left col-md-3">
        <div class="panel dark">
          <div class="row user-profile">
            <div class="col-xs-3">
                <?=$profile_pic?'<img class="profile_pic" src="'.$profile_pic.'" alt="">':'.';?></div>
            <div class="col-xs-9">
              <p class="text-large"><span class="text-small block">Hello,</span> <?=$name;?> </p>
              <div class="btn-group user-options clearfix"> <a class="btn btn-xs btn-transparent-grey dropdown-toggle" data-toggle="dropdown" href="#"> <span class=" text-extra-small"> <?=$account_id;?> (main) </span>&nbsp;<i class="fa fa-caret-down" aria-hidden="true"></i> </a>
                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="drop2">
			<?php
			$ar=array();$n=0;
			foreach($listAcc as $type=>$row0){
			//echo 'type:'.$type;
				$txt=' ';
				foreach($row0 as $row1){
					$url=site_url('member/account_id/'.$row1[0]);
					if($row1[1]!='') $url.="?agent=".$row[1];
					//echo_r($row1);
					$txt.='<li><a href="'.$url.'">'.
					$row1[0].
					' ('.$type.') </a></li>';
				}
				$ar[]=$txt;
				$n++;
			}
			//echo_r($ar);
			echo implode('<li role="separator" class="divider"></li>', $ar);
			unset($ar);
			
				?>
                  <li role="separator" class="divider"></li>
                  <li><a href="<?=site_url($active_controller.'/register/account/'.$reg_id);?>">New Account</a></li>
				  <!--li><a href="<?=site_url('member/register/agent');?>">New Agent </a></li-->
                  <li role="separator" class="divider"></li>
                  <li><a href="<?=site_url('deposit-form');?>">Add Deposit</a></li>
				  <li><a href="<?=site_url('withdraw-form');?>">Make Withdraw </a></li>
                </ul>
              </div>
              <!--a class="btn btn-transparent-grey btn-xs" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Account Management &nbsp;<i class="fa fa-cogs" aria-hidden="true"></i> </a--> </div>
          </div>
          <div class="panel-body no-padding">
            <div class="collapse" id="collapseExample" aria-expanded="true" style="">
              <div class="list-group no-margin">
			  <a class="list-group-item no-radius" href="<?=base_url('member/detail');?>"> Detail</a>
			  <a class="list-group-item" href="<?=base_url('member/edit');?>#"> Edit Detail</a>
			  <a class="list-group-item" href="<?=base_url('member/uploads');?>"> Upload Document</a>
			  <a class="list-group-item" href="<?=base_url('member/editpassword');?>#">Edit Password</a> </div>
            </div>
          </div>
        </div>
		<?php /* Menu */
		$menu=2;
		if($menu==2){
			if(isset($userlogin['typeMember'])&&$userlogin['typeMember']=='admin'){
				$this->load->view('depan/inc/menu/menu_admin_new_view');
				$menuShow=1;
			}
                        
			if(isset($userlogin['typeMember'])&&$userlogin['typeMember']=='staff'){
				$this->load->view('depan/inc/menu/menu_staff_view');
				$menuShow=1;
			}
                        
			if(isset($userlogin['typeMember'])&&$userlogin['typeMember']=='patners'){
				$this->load->view('depan/inc/menu/menu_patners_view');
				$menuShow=1;
			}
                        
                        
			if(isset($userlogin['typeMember'])&&$userlogin['typeMember']=='member'){
				$this->load->view('depan/inc/menu/menu_user_view');
				$menuShow=1;
			}
                        
                        if(isset($userlogin['typeMember'])&&$userlogin['typeMember']=='admin_trans'){
                                $this->load->view('depan/inc/menu/menu_admin_trans_view');
                                $menuShow=1;
                        }
			
			if(!isset($menuShow)){
				$this->load->view('depan/inc/menu/menu_user_view');
			}
		}
		?>
      </div> 