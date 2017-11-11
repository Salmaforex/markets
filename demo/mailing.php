<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';
require 'run_api.php';
require 'log.php';

$url="http://advance.forex/index.php/rest/forex/all_email";
$result = run_api($url,array());
//echo '<pre>'.print_r($result,1).'</pre>';
$data=isset($result['data']['email'])?$result['data']['email']:array();
foreach($data as $row){
    $id_ok=$row['id'];
    $to=$row['to'];
    $subject = $row['subject'];
    $pesan= $row['messages'];
    
    echo "$id_ok $to $subject\n<br />";
}
//die;
echo " \n<br />\ndone\n<hr />".microtime();
 
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.salmamarket.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'admin';                 // SMTP username
    $mail->Password = 'le0L1br4';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('admin@leo.salmamarket.com', 'admin');
    $mail->addAddress('gundambison@gmail.com');     // Add a recipient
    $mail->addReplyTo('admin@leo.salmamarket.com', 'admin');
    $mail->addCC('ligerxrendy@gmail.com');
    $mail->addBCC('ligerxrendy@gmail.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the body in plain text for  HTML mail clients';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    echo "\n<br />".microtime();
    $mail->send();
    echo "\n<br />Message has been sent for NOW";
    echo "\n<br />".microtime();
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
echo "----";