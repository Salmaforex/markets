<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?php
$rate=$this->forex_model->currency_by_code( $detail['currency']);
/*
Array
(
    [id] => 18
    [types] => widtdrawal
    [created] => 2017-05-01 16:50:45
    [status] => 1
    [email] => gundambison99@gmail.com
    [accountid] => -
    [detail] => Array
        (
            [account] => 7001940
            [name] => Gunawan Wibisono
            [username] => gundambison99@gmail.com
            [phone] => 43343299
            [bank] => BCA
            [norek] => 788788689
            [namerek] => Gunawan Wibisono
            [orderWidtdrawal] => 105
            [order1] => 1370250
            [mastercode] => 721816
            [userlogin] => Array
                (
                    [users] => Array
                        (
                            [u_id] => 8022
                            [u_email] => gundambison99@gmail.com
                            [u_password] => a81ee6d8dc63c4240607a842def0eaaf364d4cfc|p01ns
                            [u_type] => 1
                            [u_status] => 1
                            [u_modified] => 2017-04-24 10:38:19
                            [u_mastercode] => 721816
                            [type_user] => member
                        )

                    [country] => Indonesia
                    [city] => Jakarta
                    [state] => Jakarta
                    [zipcode] => 141241
                    [address] => 868687686 jhfhfhg
                    [phone] => 43343299
                    [bank] => BCA
                    [bank_norek] => 788788689
                    [dob1] => 03
                    [dob2] => 04
                    [dob3] => 1997
                    [name] => Gunawan Wibisono
                    [document] => Array
                        (
                            [id] => 2147484098
                            [udoc_email] => gundambison99@gmail.com
                            [udoc_status] => 1
                            [udoc_upload] => media/uploads/doc_gundambison99gmailcom_8022.tmp
                            [filetype] => image/png
                            [modified] => 2017-04-21 11:50:12
                            [profile_pic] => media/uploads/pp_gundambison99gmailcom_8022.tmp
                            [profile_type] => 
                        )

                    [email] => gundambison99@gmail.com
                    [typeMember] => member
                    [statusMember] => ACTIVE
                    [totalAccount] => 1
                    [totalBalance] => ---
                )

            [rate] => 13050
            [info] => balance not success, credit not success
        )

)
*/
$status_title='';
if($status==1){
	$status_title='Success';
}
if($status<0||$status==2){
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
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" width="254" height="67" /></a></td>
            <td width="40%" align="center"><?=date("Y-m-d H:i:s");?></td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Withdrawal Order <?=$status_title;?><br />
        <br />
        Hello <?=$detail['name'];?>,<br />
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
              <td width="398" bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3337"><strong>: </strong><?=date("Y-m-d  ");?></td>
            </tr>
            <tr id="yui_3_16_0_1_1450323941636_3322">
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_3321"><strong>Withdrawal from account</strong></td>
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
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_9"><strong>Withdrawal Amount ( USD )</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_10"><strong>: </strong>$ <?=number_format($detail['orderWidtdrawal'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_11"><strong>Withdrawal Amount ( <?=$rate['code'];?> )</strong></td>
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
<?php
/*
<tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_11"><strong>Deposit Amount ( <?=$rate['code'];?> )</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_12"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($detail['order1'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_13"><strong>Rate</strong></td>
              <td bgcolor="#CCCCCC" id="yui_3_16_0_1_1450323941636_14"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($detail['rate'],2 );?></td>
            </tr>
*/
?>

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
