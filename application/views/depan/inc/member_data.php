<?php
if(isset($show_member_data)){
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';
$profile_pic=$userlogin['document']['profile_pic']!=''?site_url('member/show_profile/'.$userlogin['document']['id'].'/100'):false;
?>
        <div class="panel">
          <div class="panel-heading partition-blue">
            <span class="text-bold">Member Data</span>
          </div>
          <div class="panel-body no-padding partition-light-grey">
            <div class="row no-margin">
             <div class="col-md-3 no-padding">
             <div class="padding-15 text-center">
             	<img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="<?=$profile_pic;?>">
             </div>
             </div>

              <div class="col-md-9 no-padding partition-white">
                <form action="#">
                  <table class="table table-striped editable">
                    <tbody>
                      <tr>
                        <td>Username</td>
                        <td class="text-right"> <?=isset($userlogin['email'])?$userlogin['email']:' ';?></td>
                      </tr>
                      <tr>
                        <td>Name</td>
                        <td class="text-right"><?=$name;?></td>
                      </tr>
                      <tr class="active">
                      <td>Master Password</td>
                      <td class="text-right"><?=isset($userlogin['users']['u_mastercode'])?$userlogin['users']['u_mastercode']:'-';?></td>
                    </tr>
                    <tr>
                      <td>Account Type</td>
                      <td class="text-right">
					  <?=isset($userlogin['accounttype'])?$userlogin['accounttype']:'MEMBER.';?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td>Account Status</td>
                      <td class="text-right">Active</td>
                    </tr>
                    <tr >
                      <td>Phone Number</td>
                      <td class="text-right">
					  <?=isset($userlogin['phone'])?$userlogin['phone']:' ';?>
					  </td>
                    </tr>
                    <tr class="active">
                      <td colspan=2>Address</td>
                    </tr>
                    <tr>
                      <td colspan=2><?=isset($userlogin['address'])?nl2br($userlogin['address']):' ';?></td>
                    </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
          <div class="panel-footer padding-15 text-right"> <a href='<?=site_url('member/edit');?>' class="btn btn-green edit">Edit Account</a> <a class="btn btn-default cancel hide">Cancel</a> <a class="btn btn-green save hide">Save</a> </div>
        </div>
<?php 
}
      