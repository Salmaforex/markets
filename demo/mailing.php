<?php
$site="bukan.advance.forex";
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';
require 'run_api.php';
require 'log.php';
require 'config.php';

$url="{$site}/index.php/rest/email/index";
$result = run_api($url,array());
echo "\nrun:$url";
//echo '<pre>'.print_r($result,1).'</pre>';
$data=is_array($result)&&isset($result['data']['email'])?$result['data']['email']:FALSE;
echo "\n".date("Y-m-d H:i:s ").microtime();
if(!$data){
    print_r($result);
    
}else{
    foreach($data as $row){
        $id_ok=$row['id'];
        $to=$row['to'];
        $subject = $row['subject'];
        $pesan= $row['messages'];
        echo "\n".date("Y-m-d H:i:s ").microtime();
        send_email($to, $subject, $pesan,$allow);
        echo "\n".date("Y-m-d H:i:s ").microtime();
        $url="{$site}/index.php/rest/email/hide";
        $params=array('id'=>$id_ok);
        $result = run_api($url,$params );
        //print_r($result);
        
        echo "\n\nparams:\n$id_ok\n$to\n$subject\n<br />";

    }
}
echo "\n".date("Y-m-d H:i:s ").microtime();
$pesan="
Ini adalah Demo <b> dimana inputnya</b> berupa HTML.
Tolong perhatikan jam pengirimannya.
".date("Y-m-d H:i:s");

send_email('gundambison@gmail.com', 'percobaan email', $pesan,FALSE);


//die;
echo " \n<br />\ndone\n<hr />".microtime();
die; 
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



function send_email($to, $subject, $message,$allow=FALSE){
    if(!$allow){
        echo "\nEmail not send because not ALLOW";
        return FALSE;
    }
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
        $mail->setFrom('noreply@salmamarkets.com', 'no reply');
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo('noreply@salmamarkets.com', 'no reply');
        //$mail->addCC('ligerxrendy@gmail.com');
        //$mail->addBCC('ligerxrendy@gmail.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject =  $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);
        echo "\n<br />".microtime();
        if($allow){
            $mail->send();
        }
        else{
            echo "\nEmail not send because not ALLOW";   
        }
        echo "\n<br />Message has been sent for NOW";
        echo "\n<br />".microtime();
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }    
    return true;
}