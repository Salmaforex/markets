<?php
//email_deposit_member_view
$email = $userlogin['email'];
$phone = $this->users_model->phone_by_email( $email );
$sms_text   =   "Deposit Order Detail";
$sms_text   .="\nAccount id : ".$post0['account'];
$sms_text   .="\nAmount(USD) : ".number_format($post0['orderDeposit'],2);
$sms_text   .="\nAmount(".$rate['code'].") : ";
$sms_text   .= number_format($post0['order1'],2);//$rate['symbol']." ".
$sms_text   .="\nRate(".$rate['code'].") : ";
$sms_text   .= number_format($rate['value'],2);
$sms_text   .="\n";
//====================SMS===================
$params=array(
   'debug'=>true,
    'number'=>$phone,
    'message'=>$sms_text."Sincerely, Finance Departement",
    'header'=>'Deposit order',
//    'local'=>true,
  'type'=>'masking'

);

if(isset($_POST['order1'])){ //memastikan bahwa ada proses POST
    $respon = smsSend($params); //ok
    logCreate($respon,'sms');
}

$params[]=$userlogin['name'];
unset($params['message']);
$params['email']=$email;
$params[] = $post0['account'];
$params[] = my_ip();
$params[] = 'deposit';
$params[] = $post0['orderDeposit'];
$params[] = $post0['order1'];
$params[] = $rate['value'];

log_info_table('transaction', $params);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" width="254" height="67" /></a></td>
            <td width="40%" align="center">January 12, 2017, 3:33:03</td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Deposit Order Detail<br />
        <br />
        Hello <?=$userlogin['name'];?>,<br />
      </p>

        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc">Thank you for submitting deposit form, Here your order deposit detail:.</td>
          </tr>
        </table>
        <table border="0" align="center" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3312">
          <tbody id="m_7063596389365770691yui_3_16_0_1_1450323941636_3311">
            <tr id="m_7063596389365770691yui_3_16_0_1_1450323941636_3324">
              <td width="276" bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3323"><strong>Date</strong></td>
              <td width="419" bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3337"><strong>: </strong><?=date("Y-m-d H:i:s");?></td>
            </tr>
            <tr id="m_7063596389365770691yui_3_16_0_1_1450323941636_3322">
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3321"><strong>Deposit to account</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3338"><strong>: </strong><?=$post0['account'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_"><strong>Name</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_2"><strong>: </strong><?=$userlogin['name'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3"><strong>Bank Name</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_4"><strong>: </strong>
			  <?=$post0['bank'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_5"><strong>Account Bank Number</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_6"><strong>: </strong><?=$post0['norek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_7"><strong>Account Bank Holder</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_8"><strong>: </strong><?=$post0['namerek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_9"><strong>Deposit Amount ( USD )</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_10"><strong>: </strong>$ <?=number_format($post0['orderDeposit'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_11"><strong>Deposit Amount ( <?=$rate['code'];?> )</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_12"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($post0['order1'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_13"><strong>Rate ( <?=$rate['code'];?> )</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_14"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($rate['value'] ,2);?></td>
            </tr>
          </tbody>
        </table>
        <p>Please transfer in accordance with the amount of transfer listed above , the maximum transfer time 1x24 hours . 
            If the transfer is not in that time period , then the system will automatically cancel the order. 
            Hopefully this information is useful .<br />
    </p>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Our Bank information :</p>
        <h3>
<?php
$email = $userlogin['email'];
$phone = $this->users_model->phone_by_email($email);
$sms_text="Deposit Order Detail";
$sms_text.="\naccount:".$post0['account'];
$sms_text.="\nAmount (USD):".number_format($post0['orderDeposit'],2);
$sms_text.="\nAmount (".$rate['code']."): ";
$sms_text.=$rate['symbol']." ".number_format($post0['order1'],2);
$sms_text.="\nRate (".$rate['code']."): ";
$sms_text.=$rate['symbol']." ".number_format($rate['value'],2);
$sms_text.="\n";
//====================SMS===================
$params=array(
   'debug'=>true,
    'number'=>$hp_send,
    'message'=>$sms_text."Sincerely, Customer Service.",
//    'local'=>true,
  'type'=>'masking'

);

//$respon = smsSend($params);

        $bank = $key=$this->config->item('forex_bank');
        foreach($bank[$rate['code']] as $row){
                echo "\n\t{$row['name']} : <strong>{$row['number']}</strong> a.n {$row['person']}<br />";
        }
?>  
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