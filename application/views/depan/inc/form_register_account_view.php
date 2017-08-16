<?php
//register_account.php
if(!isset($controller_main))
	$controller_main='member';

$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
$email=isset($userlogin['email'])?$userlogin['email']:'';
$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';
//if(defined('LOCAL')){ echo_r($accounts);}
?>
       <form action="<?=site_url($controller_main.'/register_post/account');?>#" role="form" class="form-horizontal" id="validate"  method="POST"  >
          	<div class="text-center marbot-30">
            	<h3>Create Additional Live Account Salmamarkets</h3>
              <p>Since you have existing live accounts please use this form to create your additional live account.</p>
            </div>
            <div class="content" style="display: block;">
              <div class="form-group">
                <label class="col-sm-3 control-label"> Account emaill <span class="symbol required"></span> </label>
                <div class="col-sm-7">
                  <input class="form-control" id="stp" name="email" placeholder="STP" type="text" value='<?=$email;?>' readonly />
                </div>
              </div>
              <div class="form-group" style='display:none'>
                <label class="col-sm-3 control-label"> Account Agent  </label>
                <div class="col-sm-7">
                  <input class="form-control" id="stp" name="agent" placeholder="id agent" type="text" value='<?=$agent_default;?>' />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label"> Master Code <span class="symbol required"></span> </label>
                <div class="col-sm-7">
                  <input class="form-control" id="code" name="code" placeholder="Master Code" type="text">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-7 col-sm-offset-3">
                  <label class="checkbox-inline"><input name="agree" value="" type="checkbox"> I accept user agrement.</label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3 col-sm-offset-3">
                  <button type="submit" class="btn btn-green"> Open Account </button>
                </div>
              </div>
            </div>
            </form>

            <div class="vspace-30"></div>