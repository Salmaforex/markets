<?php
$account_agent = $account_member = array();
foreach ($account as $row) {
    if ($row['type'] == 'AGENT') {
        $account_agent[] = $row['accountid'];
    } else {
        $account_agent[] = $row['accountid'];
    }
}
if (true) {
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
                                        <td width="60%" height="94"><a 
                                                href="https://www.salmamarkets.com/"><img 
                                                    src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" 
                                                    width="254" height="67" /></a>
                                        </td>
                                        <td width="40%" align="center"><?= date("m D Y H:i:s"); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr align="center" noshade="noshade" /></td>
                    </tr>
                    <tr>
                        <td><p>Welcome to Salma Markets<br />
                                <br />
                                Hello ,<br />
                                This email only for Member Only. Please validate your Account detail on <?= site_url(); ?>
                            </p>
                            <table width="641" border="0">
                                <tr>
                                    <td width="572" height="33" bgcolor="#0099cc">Access to your Personal Area:</td>
                                </tr>
                            </table>
                            <table id="yui_3_16_0_ym19_1_1485496357980_2580">
                                <tbody id="yui_3_16_0_ym19_1_1485496357980_2579">
                                    <tr id="yui_3_16_0_ym19_1_1485496357980_2841">
                                        <td width="362" bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2840">Login 
                                            <em id="yui_3_16_0_ym19_1_1485496357980_2839">(used to login to your Personal Area)</em></td>
                                        <td width="178" id="yui_3_16_0_ym19_1_1485496357980_2949"><?= trim($username); ?></td>
                                    </tr>
                                    <tr id="yui_3_16_0_ym19_1_1485496357980_2841">
                                        <td width="362" bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2840">Phone 
                                            <em id="yui_3_16_0_ym19_1_1485496357980_2839">(Please Update if not correct)</em></td>
                                        <td width="178" id="yui_3_16_0_ym19_1_1485496357980_2949"><?= trim($phone); ?></td>
                                    </tr>

                                    <tr id="yui_3_16_0_ym19_1_1485496357980_2578">
                                        <td bgcolor="#FFFFFF" id="yui_3_16_0_ym19_1_1485496357980_2786">Member Account MT4 
                                            <em id="yui_3_16_0_ym19_1_1485496357980_2785">(Member)</em></td>
                                        <td id="yui_3_16_0_ym19_1_1485496357980_2577"><ol><li><?= implode("\n<li>", $account_member); ?></ol></td>
                                    </tr>

                                </tbody>
                            </table>
                            <p> <a href="https://www.salmamarkets.com/metatrader-4-for-windows/"><img
                                        src="https://www.salmamarkets.com/wp-content/uploads/2017/04/download_metatrader-1-1.jpg"
                                        width="263" height="72" /></a><br />
                            </p>
                            <p>Your Personal Area at 
                                <a href="<?= base_url('login/member'); ?>">
                                    <?= site_url('login/member'); ?></a> 
                                is your best tool to manage your account(s). 
                                You can deposit your account, withdraw from your account, view stats, take part in contests and many more.<br />
                                If The information above not correct. Please info our Customer Service.
                            </p>
                            <hr align="center" noshade="noshade" /></td>
                    </tr>
                    <tr>
                        <td><p>Please do not reply this email. 
                                Because the mailbox is not being monitored so you wont get any reply. 
                                For help, please login to your Salma Markets account and click in Live Support icon in the left side of page.</p>
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
}


$sms_text = "Account validation for $username\n";
$sms_text.="This message only to inform list of your account.\n";
$sms_text.="\nAgent: " . implode("\n", $account_agent);
$sms_text.="\nMember: " . implode("\n", $account_member);

$sms_text.="\nIf the account list not valid, inform our CS";
//====================SMS===================

$params = array(
    'debug' => true,
    'number' => $phone,
    'message' => $sms_text . "Sincerely, System.",
//   'local'=>true,
//  'type'=>'masking'
);

if (isset($allow_sms)) {
    $respon = smsSend($params);
}
else{
    echo 'no sms send';
}