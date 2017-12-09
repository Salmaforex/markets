<?php
//echo_r($userlogin);die();
$name = isset($userlogin['name']) && $userlogin['name'] != '' ? $userlogin['name'] : 'User Forex';
//." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
//$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';
$account_detail = $this->account->detail($accountid, 'accountid');
$balance = isset($account_detail['balance']) ? $account_detail['balance'] : array();
$summary = isset($account_detail['summary']) ? $account_detail['summary'] : array();

$type_member = isset($account_detail['type']) ? $account_detail['type'] : 'Member.';
if ($type_member == '') {
    $type_member = 'Member';
} else {
    $type_member = ucfirst(strtolower($type_member));
}
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
                    <p>Since you have existing live accounts please use this form to create your additional live account.
                    </p>

                    </p>
                </div>
            </div>
            <div class="panel panel-white">
                <div class="panel-heading partition-blue"> <span class="text-bold"> User Detail </span> </div>
                <div class="panel-body no-padding">
                    <div class="row no-margin">

                        <div class="col-md-6 no-padding">
                            <table class="table no-margin" id="">
                                <tbody>
                                    <tr class="active">
                                        <td>Account (Main)</td>
                                        <td>:</td> <td class="text-right">
                                            <?= $accountid; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name </td>
                                        <td>:</td> <td class="text-right">
                                            <?= $name; ?>
                                        </td>
                                    </tr>
                                    <!--tr class="active">
                                      <td>Leverage</td>
                                      <td class="text-right">&nbsp;</td>
                                    </tr-->
                                    <tr>
                                        <td>Account Type</td>
                                        <td>:</td> <td class="text-right">
                                            <?= $type_member; ?>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td>Account Status</td>
                                        <td>:</td> <td class="text-right">Active</td>
                                    </tr>
                                    <tr >
                                        <td>Phone Number</td>
                                        <td>:</td> <td class="text-right">
                                            <?= isset($userlogin['phone']) ? $userlogin['phone'] : ' '; ?>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td colspan=1>Address</td>
                                        <td>:</td>  
                                        <td colspan=1 class="text-right"><?= isset($userlogin['address']) ? nl2br($userlogin['address']) : '&nbsp; '; ?></td>
                                    </tr>
                                    <tr >
                                        <td>Withdrawal</td>
                                        <td>:</td> 
                                        <td class="text-right">
                                            <?= isset($summary['TotalWithdrawal']) ? number_format($summary['TotalWithdrawal'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td colspan=1>Deposit</td>
                                        <td>:</td> 
                                        <td class="text-right">
                                            <?= isset($summary['TotalDeposit']) ? number_format($summary['TotalDeposit'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 no-padding">
                            <table class="table no-margin" id="">
                                <tbody>
                                    <tr class="active">
                                        <td>Balance</td>
                                        <td>:</td> <td class="text-right">
                                            <?= isset($balance['Balance']) ? number_format($balance['Balance'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Credit</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($balance['Credit']) ? number_format($balance['Credit'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td>Equity</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($balance['Equity']) ? number_format($balance['Equity'], 2) : 0; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Free Margin</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($balance['FreeMargin']) ? number_format($balance['FreeMargin'], 2) : 0; ?>
                                        </td>
                                    </tr>

                                    <tr class="active">
                                        <td>Total Open Transaction</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($summary['TotalOpennedTransactions']) ? number_format($summary['TotalOpennedTransactions'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Total Floating Transaction</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($summary['TotalFloatingTransactions']) ? number_format($summary['TotalFloatingTransactions'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Total Close Transaction</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($summary['TotalClosedTransactions']) ? number_format($summary['TotalClosedTransactions'], 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td>Total Open Volume Transaction</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($summary['TotalOpennedVolumeTransaction']) ? number_format($summary['TotalOpennedVolumeTransaction'] / 100, 2) : 0; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Total Close Volume Transaction</td> 
                                        <td>:</td> <td class="text-right">
                                            <?= isset($summary['TotalClosedVolumeTransaction']) ? number_format($summary['TotalClosedVolumeTransaction'] / 100, 2) : 0; ?>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('depan/inc/account_list'); ?>
        </div>
    </div>
</div>
<?php

if (false) {
    /*
      [TotalProfit] => -3.51
      [TotalTransactions] => 24
      [TotalOpennedTransactions] => 22
      [TotalFloatingTransactions] => 0
      [TotalClosedTransactions] => 22
      [TotalOpennedVolumeTransaction] => 23
      [TotalFloatingVolumeTransaction] => 0
      [TotalClosedVolumeTransaction] => 23
      [TotalVolume] => 22
      [TotalCommission] => 0
      [TotalAgentCommission] => 0
      [TotalWithdrawal] => 0
      [TotalDeposit] => 100
     */
    echo_r($account_detail);
    echo_r($userlogin);
}