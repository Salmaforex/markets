<?php

if(!isset($show_html)){
ob_start();
}
else{
	//die('iik');
}
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
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" width="254" height="67" /></a></td>
            <td width="40%" align="center"><?=date("m D Y H:i:s");?></td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td height="342"><p>Confirmation to Recover Personal Area password<br />
        <br />
        Hello <?=$user['name'];?>,<br />
      </p>
        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc">Recovery Personal Area Password:</td>
          </tr>
        </table>
        <p>Thank you for submitting Recovery form, Here your detail:.<br />
    </p>
        <p><a href="<?=site_url('guest/recover/'.$recoverid);?>" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=id&amp;q=https://secure.salmamarkets.com/recover/<?=$recoverid;?>&amp;source=gmail&amp;ust=1485588468725000&amp;usg=AFQjCNGbTlfjFeOSWSl3ARJN_yuMwl11LA">
                Click Here to Generate Your Recovery Password</a>. The link Expired Soon<br />
          <br />
          Ignore if you not request this <br />
        </p>
        <p>Sincerely,<br />
          System<br />
      </p>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Please do not reply this email. Because the mailbox is not being monitored so you wont get any reply. For help, please login to your Salma Markets account and click in Live Support icon in the left side of page.</p>
	  <p>Your Personal Area at 
		<a href="<?=base_url('login/member');?>">
		<?=site_url('login/member');?></a> 
		is your best tool to manage your account(s). You can deposit your account, withdraw from your account, view stats, take part in contests and many more.<br />
        </p>
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

<?php
if(!isset($show_html)){
	$message = ob_get_contents();
	ob_end_clean(); 
	$to = trim($email);

	$subject = 'Confirmation to Recover Personal Area password  ' ;

	$headers = "From: noreply@$salmamarkets.com\r\n";
	$headers .= "Reply-To: noreply@salmamarkets.com\r\n";
	//$headers .= "CC: susan@example.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";

	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	//if(isset($show_local))
	//echo $message;
	//===============================

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
		//$this->db->insert($this->forex_model->tableAPI,$data);
		$subject = '[SalmaMarkets] Confirmation to Recover Personal Area password';
		foreach($emailAdmin as $to){
			batchEmail(trim($to), $subject, $message, $headers);
		}

}
else{}