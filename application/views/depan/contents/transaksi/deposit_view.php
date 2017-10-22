<?php
//deposit_view.php
if (function_exists('logFile')) {
    logFile('view/member/data', 'widtdrawalProcess_data.php', 'data');
};
$name = isset($userlogin['name']) && $userlogin['name'] != '' ? $userlogin['name'] : 'user forex';
$u_detail = $userlogin;
defined('BASEPATH') OR exit('No direct script access allowed');
//if(defined('LOCAL')) echo_r($account_list);
$accounts = array();
foreach ($account_list as $row) {
    if (isset($row['id'])) {
        $acc_detail = $this->account_model->gets($row['id']);
//		if(defined('LOCAL')) echo_r($acc_detail);
        $accounts[$acc_detail['accountid']] = $name . ' ( ' . $acc_detail['accountid'] . ' )';
    }
}

//=========currency
$currency = array();
$currency_list = $this->forex->currency_list();
foreach ($currency_list as $row) {
    $currency[$row['code']] = $row['name'] . ' ( ' . $row['symbol'] . ' )';
}

if (defined('LOCAL')) {
//	echo_r($accounts);
//	echo_r($u_detail);
}
if (!isset($controller_main))
    $controller_main = 'member';


$message = $this->session->flashdata('messages');
if($message==''){
    $messages=false;
}
//=============

/*
  $notAllow=1;
  $detail=$this->account->detail($userlogin['id']);
  //print_r($detail);die();
  if(isset($detail['document']['status'])){
  if($detail['document']['status']==1){
  unset($notAllow);
  }
  else{
  $this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Dokumen pendukung sedang di review'));
  }
  }
  else{
  $this->session->set_flashdata('notif', array('status' => false, 'msg' => 'Silakan Upload dokumen pendukung!'));
  }

  if(isset($notAllow)){
  redirect(site_url("member/uploads/warning"),1);
  }
 */
?>
<div class="container">
    <div class="row">
        <?php
        $this->load->view('depan/inc/left_view');
        ?>
        <div class="main col-md-9">
            <?php
            $this->load->view('depan/inc/partner_top_view');
            ?>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    $this->load->view('depan/inc/account_balance_view');
                    ?>

                </div>
                <div class="col-md-4 col-xs-6">
                    <ul class="list-group text-dark panel-shadow">
                        <a href="<?= base_url('deposit-form'); ?>#" class="list-group-item active partition-blue"> Add Deposit <i class="fa fa-15x fa-arrow-circle-up pull-right"></i> </a>
                        <li class="list-group-item "> <a href="<?= site_url('deposit-form'); ?>#" class="block text-center"><img style="width: 89px;" 
                            src="<?= base_url('media'); ?>/images/deposit.png"></a> </li>
                    </ul>
                </div>
                <div class="col-md-4 col-xs-6">
                    <ul class="list-group text-dark panel-shadow">
                        <a href="<?= base_url('withdraw-form'); ?>#" class="list-group-item active partition-blue"> Make Withdraw <i class="fa fa-15x fa-arrow-circle-down pull-right"></i> </a>
                        <li class="list-group-item "> <a href="<?= base_url('withdraw-form'); ?>#" class="block text-center"><img style="width: 89px;" 
                            src="<?= base_url('media'); ?>/images/withdraw.png"></a> </li>
                    </ul>
                </div>
            </div>
            <div class="panel panel-white">
                <div class="panel-heading border-light">
                    <h3><strong>Welcome to secure area</strong></h3>

                </div>
            </div>
            <div class1="panel panel-white">
                <div class1="panel-heading partition-blue"> <!--span class="text-bold"> Summary</span--> </div>
                <div class="panel-body no-padding">
                    <div class="row no-margin">
                        <!--start here-->
                        <?php
//	$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
//	$urlAffiliation=base_url("register/{$rand_url}");

                        if (isset($done) && $done == 1) {
                            $load_view = isset($baseFolder) ? $baseFolder . 'inc/done_view' : 'done_view';
                            $this->load->view($load_view);
                        }

                        if (isset($show_done)) {
                            $load_view = isset($baseFolder) ? $baseFolder . 'inc/deposit_done_view' : 'deposit_done_view';
                            $this->load->view($load_view);
                        }
                        
                        if($message){
                            $load_view = isset($baseFolder) ? $baseFolder . 'inc/message_view' : 'message_view';
                            $this->load->view($load_view);
                        }
                        ?>

                        <!---NEW------>
                        <div class="main col-md-12">
                            <div class="panel">
                                <div class="panel-heading partition-blue">
                                    <span class="text-bold">Deposit Option</span>
                                </div>
                                <div class="panel-body no-padding partition-white">
                                    <form class="form-horizontal" method="POST" id="depositForm">
                                        <div class="vspace-30"></div>
                                        <div class="row no-margin">
                                            <div class="col-md-12 no-padding">
                                                <div class="content" style="display: block;">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Account SalmaForex <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <?php
                                                            $name = isset($userlogin['name']) ? $userlogin['name'] : '';
                                                            $name1 = '<input type="hidden" name="name" value="' . $name . '" />
					<input type="hidden" name="username" value="' . $userlogin['email'] . '" />';
                                                            $attributes = array(
                                                                'id' => 'input_' . $name,
                                                                'class' => 'form-control',
                                                            );
                                                            $inp = form_dropdown("account", $accounts, '', $attributes);
                                                            echo $inp;
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Currency <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <?php
                                                            $attributes = array(
                                                                'id' => 'input_currency',
                                                                'class' => 'form-control',
                                                            );
                                                            $inp = form_dropdown("currency", $currency, '', $attributes);
                                                            echo $inp;
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Name <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <?= $name . $name1; ?>
                                                        </div>
                                                    </div>
                                                    <!--div class="form-group">
                                                          <label class="col-sm-4 control-label">Phone <span class="symbol required"></span> </label>
                                                          <div class="col-sm-7">
                                                            <input class="form-control" id="accnumber" name="phone" placeholder="Account number" type="text" value='<?= trim($userlogin['phone']); ?>' />
                                                          </div>
                                                    </div-->
                                                    <!--div class="form-group">
                                                          <label class="col-sm-4 control-label"> Deposit Methode <span class="symbol required"></span> </label>
                                                          <div class="col-sm-5">
                                                            <select class="form-control" id="methode" name="methode">
                                                                  <option value="0">Indonesian Bank Transfer</option>
                                                                  <option value="0">International Bank Transfer</option>
                                                            </select>
                                                          </div>
                                                          <div class="col-sm-2">
                                                            <select class="form-control" id="currency" name="currency">
                                                                  <option value="0">IDR</option>
                                                                  <option value="0">USD</option>
                                                            </select>
                                                     </div>
                                                    </div-->
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Bank Name <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" id="accnumber" name="bank" placeholder="Account number" type="text" value='<?= trim($userlogin['bank']); ?>' />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Bank account number <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" id="accnumber" name="norek" placeholder="Account number" type="text" value='<?= trim($userlogin['bank_norek']); ?>'>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Bank account owner Name <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" id="accnumber" name="namerek" placeholder="Account number" type="text" value='<?= trim($name); ?>' />
                                                        </div>
                                                    </div>
                                                    <?php /*
                                                      echo bsInput('Jumlah Transfer (Rp)','order1', '' ,'Nominal Hanya Estimasi Saja' );
                                                     */ ?>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Rate <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <!--input class="form-control" id="rate" name="rate" placeholder="Rate" type="text" readonly value=''/--><div id="input_rate">0</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Deposit amount <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="orderDeposit" value="10" id="input_orderDeposit" class="form-control" placeholder="Minimal $10"  />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> Total <span class="symbol required"></span> </label>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="order1" value="" id="input_order1" class="form-control" placeholder="Nominal Hanya Estimasi Saja" readonly  />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"> submit   </label>
                                                        <div class="col-sm-4">
                                                            <!--input class="form-control" id="code" name="mastercode" placeholder="Master Code" type="text" /--> 
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button type="submit" class="btn btn-green" name="submit" value="normal"> Deposit <i class="fa fa-arrow-circle-right"></i> </button>
                                                <?php   if(isset($button_fasapay)){?>
                                                            <button type="submit" class="btn btn-blue" name="submit" value="fasapay"> Deposit Fasapay <i class="fa fa-arrow-circle-right"></i> </button>
                                                <?php   } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="vspace-30"></div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    urlDeposit = "<?= site_url("forex/deposit_value") . "?t=" . date("ymdhis"); ?>";
</script>