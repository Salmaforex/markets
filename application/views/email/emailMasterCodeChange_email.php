<?php

$email = $user['email'];
$phone = $this->users_model->phone_by_email( $email );
$sms_text   =   "Update Master Code";
$sms_text   .="\Hello ".$user['name'];
$sms_text   .="\nAccess to your Personal Area Updated\n";
$sms_text   .="\nLogin: ".$email;
$sms_text   .="\nMaster code: ";
$sms_text   .=$password;

//$sms_text   .=$rate['symbol']." ".number_format($rate['value'],2);
$sms_text   .="\n";
//====================SMS===================
$params=array(
   'debug'=>true,
    'number'=>$phone,
    'message'=>$sms_text."Sincerely, Customer Service.",
    'header'=>'master code update',
//    'local'=>true,
  'type'=>'masking'

);

//$respon = smsSend($params);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="650" border="0" align="center" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>

	  <table width="100%" cellpadding="2">
        <tbody>
          <tr>
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" width="254" height="67" /></a></td>
            <td width="40%" align="center">January 12, 2017, 3:33:03</td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><h2>Update Master Code</h2><br />
        <br />
        Hello <?=$user['name'];?>,<br />
      </p>
        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc">Access to your Personal Area:</td>
          </tr>
        </table>
        <table id="yui_3_16_0_ym19_1_1485496357980_2580">
          <tbody id="yui_3_16_0_ym19_1_1485496357980_2579">
            <tr id="yui_3_16_0_ym19_1_1485496357980_2841">
              <td width="362" bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2840">Login <em id="yui_3_16_0_ym19_1_1485496357980_2839">(used to login to your Personal Area)</em></td>
              <td width="178" id="yui_3_16_0_ym19_1_1485496357980_2949">
			  <?=$user['email'];?></td>
            </tr>
            <tr id="yui_3_16_0_ym19_1_1485496357980_2837">
              <td bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2836">Master Code <em id="yui_3_16_0_ym19_1_1485496357980_2835"> </em></td>
              <td id="yui_3_16_0_ym19_1_1485496357980_3067"><?=$password;?></td>
            </tr>
          </tbody>
        </table>
        <p>Your Personal Area at <a href="https://secure.salmamarkets.com/login/member">https://secure.salmamarkets.com/login/member</a> is your best tool to manage your account(s). You can deposit your account, withdraw from your account, view stats, take part in contests and many more.<br />
        </p>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Please do not reply this email. Because the mailbox is not being monitored so you wont get any reply. 
              For help, please login to your Salma Markets account and click in Live Support icon in the left side of page.</p>
        <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Copyright © 2014 Salma Markets. All rights reserved.</p>
        <p>Sincerely, Customer Service<br />
          Users are advised to read the terms and conditions carefully.</p>
        <p>Salma Markets Email</p></td>
    </tr>
  </tbody>
</table>
</body>
</html>