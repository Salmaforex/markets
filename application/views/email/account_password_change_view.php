<?php

$email = $userlogin['email'];
$phone = $this->users_model->phone_by_email( $email );
$sms_text   =   "Update Account MT4 Trading Detail";
$sms_text   .="\Hello ".$userlogin['name'];
$sms_text   .="\nAccess to to Your MT4 Account Updated\n";
$sms_text   .="\nLogin: ".$username;
$sms_text   .="\nTrading: ".$masterpassword;
$sms_text   .="\nInvestor: ".$investorpassword;

//$sms_text   .=$rate['symbol']." ".number_format($rate['value'],2);
$sms_text   .="\n";
//====================SMS===================
$params=array(
   'debug'=>true,
    'number'=>$phone,
    'message'=>$sms_text."Sincerely, Customer Service.",
    'header'=>'account password update',
//    'local'=>true,
//  'type'=>'masking'

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
      <td><table width="100%" cellpadding="2">
        <tbody>
          <tr>
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img 
			src0="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" 
			src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png"
			width="254" height="67" /></a></td>
            <td width="40%" align="center"><?=date("Y-m-d H:i:s");?></td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Update Account MT4 Trading Detail<br />
        <br />
        Hello <?=$userlogin['name'];?>,<br />
      </p>
        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc">Access to Your MT4 Account:</td>
          </tr>
        </table>

        <table id="yui_3_16_0_ym19_1_1485496357980_2580">
          <tbody id="yui_3_16_0_ym19_1_1485496357980_2579">
            <tr id="yui_3_16_0_ym19_1_1485496357980_2841">
              <td width="197" id="yui_3_16_0_ym19_1_1485496357980_2840">Account Number</td>
              <td width="343" id="yui_3_16_0_ym19_1_1485496357980_2949"><?=$username;?></td>
            </tr>
            <tr id="yui_3_16_0_ym19_1_1485496357980_2837">
              <td id="yui_3_16_0_ym19_1_1485496357980_2836">Trading Password</td>
              <td id="yui_3_16_0_ym19_1_1485496357980_3067"><?=$masterpassword;?></td>
            </tr>
            <tr id="yui_3_16_0_ym19_1_1485496357980_2578">
              <td id="yui_3_16_0_ym19_1_1485496357980_2786">Investor Password</td>
              <td id="yui_3_16_0_ym19_1_1485496357980_2577"><?=$investorpassword;?></td>
            </tr>
            <tr>
              <td id="yui_3_16_0_ym19_1_1485496357980_">MT4 Server</td>
              <td id="yui_3_16_0_ym19_1_1485496357980_2">SalmaMarkets-Live </td>
            </tr>
          </tbody>
        </table>
        <p> <a href="https://www.salmamarkets.com/metatrader-4-for-windows/"><img
		src="https://www.salmamarkets.com/wp-content/uploads/2017/04/download_metatrader-1-1.jpg"
		width="263" height="72" /></a><br />
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
