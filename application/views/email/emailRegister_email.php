<?php
if(isset($name)){
	ob_start();
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
		  <td><p>Welcome to Salma Markets<br />
			<br />
			Hello <?=$name;?>,<br />
		  </p>
			<table width="641" border="0">
			  <tr>
				<td width="572" height="33" bgcolor="#0099cc">Access to your Personal Area:</td>
			  </tr>
			</table>
			<table id="yui_3_16_0_ym19_1_1485496357980_2580">
			  <tbody id="yui_3_16_0_ym19_1_1485496357980_2579">
				<tr id="yui_3_16_0_ym19_1_1485496357980_2841">
				  <td width="362" bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2840">Login <em id="yui_3_16_0_ym19_1_1485496357980_2839">(used to login to your Personal Area)</em></td>
				  <td width="178" id="yui_3_16_0_ym19_1_1485496357980_2949"><?=trim($username);?></td>
				</tr>
				<tr id="yui_3_16_0_ym19_1_1485496357980_2837">
				  <td bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2836">Password <em id="yui_3_16_0_ym19_1_1485496357980_2835">(used as password to your Personal Area)</em></td>
				  <td id="yui_3_16_0_ym19_1_1485496357980_3067"><?=trim($password);?></td>
				</tr>
				<tr id="yui_3_16_0_ym19_1_1485496357980_2578">
				  <td bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2786">Master Code <em id="yui_3_16_0_ym19_1_1485496357980_2785">(used for withdrawal and others)</em></td>
				  <td id="yui_3_16_0_ym19_1_1485496357980_2577"><?=trim($mastercode);?></td>
				</tr>
			  </tbody>
			</table>
        <p> <a href="https://www.salmamarkets.com/metatrader-4-for-windows/"><img
		src="https://www.salmamarkets.com/wp-content/uploads/2017/04/download_metatrader-1-1.jpg"
		width="263" height="72" /></a><br />
        </p>
			<p>Your Personal Area at 
			<a href="<?=base_url('login/member');?>">
			<?=site_url('login/member');?></a> 
			is your best tool to manage your account(s). You can deposit your account, withdraw from your account, view stats, take part in contests and many more.<br />
			</p>
		  <hr align="center" noshade="noshade" /></td>
		</tr>
		<tr>
		  <td><p>Please do not reply this email. Because the mailbox is not being monitored so you wont get any reply. For help, please login to your Salma Markets account and click in Live Support icon in the left side of page.</p>
			<hr align="center" noshade="noshade" /></td>
		</tr>
		<tr>
		  <td><p>Copyright © 2014 Salma markets. All rights reserved.</p>
			<p>Sincerely, Customer Service<br />
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
	$to = trim($email);

	$subject = '[SalmaMarkets] Register ';

	$headers = "From: noreply@salmamarkets.com\r\n";
	$headers .= "Reply-To: noreply@salmamarkets.com\r\n";
	//$headers .= "CC: susan@example.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";

	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	//if(isset($show_local))echo $message;
	//===============================
	{
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
		$subject = "[reminder] Register ";
		foreach($emailAdmin as $to){
			batchEmail(trim($to), $subject, $message, $headers);
		}

	}
}