<?php 
ob_start();
$name=!isset($post0['name'])?$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']:$post0['name'];
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
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img src="https://partners.salmamarkets.com/wp-content/uploads/2016/12/salmamarket240.png" width="254" height="67" /></a></td>
            <td width="40%" align="center"><?=date("Y-m-d H:i:s");?></td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Withdrawal Order Detail<br />
        <br />
        Hello <?=$name;?>,<br />
      </p>
        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc">Thank you for submitting withdrawal form, Here your order withdrawal detail:.</td>
          </tr>
        </table>
        <table border="0" align="center" id="yui_3_16_0_1_1450323941636_3312">
          <tbody id="yui_3_16_0_1_1450323941636_3311">
            <tr id="yui_3_16_0_1_1450323941636_3324">
              <td width="297" bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3323"><strong>Date</strong></td>
              <td width="398" bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3337"><strong>: </strong>
			  <?=date("Y-m-d H:i:s");?></td>
            </tr>
            <tr id="yui_3_16_0_1_1450323941636_3322">
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3321"><strong>Withdrawal from account</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3338"><strong>: </strong></strong><?=$raw['accountid'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_"><strong>Name</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_2"><strong>:</strong> <?=$name;?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3"><strong>Bank Name</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_4"><strong>: </strong><?=$raw['bank'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_5"><strong>Account Bank Number</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_6"><strong>: </strong><?=$raw['norek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_7"><strong>Account Bank Holder</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_8"><strong>: </strong><?=$raw['namerek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_9"><strong>Withdrawal Amount ( USD )</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_10"><strong>: </strong>$ <?=number_format($raw['orderWidtdrawal'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_11"><strong>Withdrawal Amount ( Rp )</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_12"><strong>: </strong>Rp. <?=number_format($raw['order1'],0);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_13"><strong>Rate</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_14"><strong>: </strong>Rp. <?=number_format($rate['value']);?></td>
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

<?php 
$message = ob_get_contents();
ob_end_clean();
//echo  $detail;
$to = trim( $userlogin['email'] );

$subject = '[SalmaMarkets] Confirmation to Withdrawal ';

$headers = "From: noreply@salmamarkets.com\r\n";
$headers .= "Reply-To: noreply@salmamarkets.com\r\n"; 
$headers .= "MIME-Version: 1.0\r\n";

$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

if(defined('LOCAL')){
	$rawEmail=array(
		$subject, $headers,$message,'send email'
	);
	$data=array( 'url'=>$to,
		'parameter'=>json_encode($rawEmail),
		'error'=>2
	);
	$this->db->insert($this->forex_model->tableAPI,$data);
	//die($message );
}
else{
	if(!is_array($to))$to=array($to);
	foreach($to as $email){
		batchEmail($email, $subject, $message, $headers);
	}
	$rawEmail=array(
		$subject, $headers,$message,'send email'
	);
	$data=array( 'url'=>json_encode($to),
		'parameter'=>json_encode($rawEmail),
		'error'=>2
	);
/*
	batchEmail(trim($to), $subject, $message, $headers);
	$rawEmail=array(
		$subject, $headers,$message,'send email'
	);
	$data=array( 'url'=>$to,
		'parameter'=>json_encode($rawEmail),
		'error'=>2
	);
	//$this->db->insert($this->forex_model->tableAPI,$data);
*/
}