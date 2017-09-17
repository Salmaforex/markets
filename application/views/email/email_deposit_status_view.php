<?php
$userlogin = $detail['userlogin'];
$email = $userlogin['email'];
$phone = $this->users_model->phone_by_email( $email );
$sms_text   =   "Deposit Order Detail";
$sms_text   .="\naccount:".$detail['account'];

$sms_text   .="\nStatus: ".$status_title;
$sms_text   .="\nAmount (USD):".number_format($detail['orderDeposit'],2);
$sms_text   .="\nAmount (".$rate['code']."): ";
$sms_text   .=$rate['symbol']." ".number_format($detail['order1'],2);
//$sms_text   .=$rate['symbol']." ".number_format($rate['value'],2);
$sms_text   .="\n";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?php
$rate=$this->forex_model->currency_by_code( $detail['currency']);

$status_title='active';
//echo_r($detail);
//$status = $detail['status'];
if($status==1){
	$status_title='Success';
    //====================SMS===================
    $params=array(
       'debug'=>true,
        'number'=>$phone,
        'message'=>$sms_text."Sincerely, Finance Departement.",
        'header'=>'Deposit status '.$status_title,
    //    'local'=>true,
    //  'type'=>'masking'

    );

    //$respon = smsSend($params);
    //logCreate($respon,'sms');
}

if($status==-1||$status==2){
	$status_title='Cancelled';
}
?>
<body>
<table width="650" border="0" align="center" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td><table width="100%" cellpadding="2">
        <tbody>
          <tr>
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/">
                    <img src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" width="254" height="67" />
                </a></td>
            <td width="40%" align="center"><?=date("Y-m-d H:i:s");?></td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Deposit Order <?=$status_title;?><br />
        <br />
        Hello <?=$detail['name'];?>,<br />
      </p>
        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc">Thank you for submitting Deposit form, Here your order Deposit detail Status:.</td>
          </tr>
        </table>
        <table border="0" align="center" id="yui_3_16_0_1_1450323941636_3312">
          <tbody id="yui_3_16_0_1_1450323941636_3311">
            <tr id="yui_3_16_0_1_1450323941636_3324">
              <td width="297" bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3323"><strong>Date</strong></td>
              <td width="398" bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3337"><strong>: </strong><?=date("Y-m-d  ");?></td>
            </tr>
            <tr id="yui_3_16_0_1_1450323941636_3322">
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3321"><strong>Deposit from account</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3338"><strong>: </strong><?=$detail['account'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_"><strong>Name</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_2"><strong>:</strong> <?=$detail['name'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3"><strong>Bank Name</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_4"><strong>: </strong><?=$detail['bank'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_5"><strong>Account Bank Number</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_6"><strong>: </strong><?=$detail['norek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_7"><strong>Account Bank Holder</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_8"><strong>: </strong><?=$detail['namerek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_9"><strong>Deposit Amount ( USD )</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_10"><strong>: </strong>$ <?=number_format($detail['orderDeposit'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_11"><strong>Deposit Amount ( <?=$rate['code'];?> )</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_12"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($detail['order1'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_13"><strong>Rate</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_14"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($detail['rate'],2 );?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_13"><strong>Status</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_14"><strong>: </strong> <?=$status_title;?></td>
            </tr>


          </tbody>
        </table>
        <br />
        <p>Please wait 1-3 x 24 hours , your order will be forwarded to the Finance Departement for in the process.salmamarkets finance working hours from Monday - Friday at 09:00 am - 17:00 pm . Hopefully this information is useful .<br />
</p>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Please do not reply this email. Because the mailbox is not being monitored so you wont get any reply. For help, please login to your Salma Markets Forex account and click in Live Support icon in the left side of page.</p>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Copyright © 2014 Salma Markets. All rights reserved.</p>
        <p>Sincerely, Finance Departement<br />
          Users are advised to read the terms and conditions carefully.</p>
      <p>Salma Markets Email</p></td>
    </tr>
  </tbody>
</table>
</body>
</html>
