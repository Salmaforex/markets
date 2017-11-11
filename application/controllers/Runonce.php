<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'/libraries/SmtpApi.php');
class Runonce extends CI_Controller {

    private $db_main;

    function index() {

        die;

        echo "start";
        $params = array(
            'api' => 'users',
            'function' => 'exist',
            'data' => array('admin')
        );
        for ($i = 7902438; $i >= 7902415; $i--) {


            $this->check_account(7000051);
        }
        //$this->parse_account();
        //die;
        // $p=_runApi('http://demo.salmamarkets.com/index.php/rest/users',$params);
        //print_r($p);
        //die;
        //$this->send_msg_to_member();
        //$this->send_msg_to_agent();
    }

    function testing_email() {
        $this->load->library('session');
        $hp_send = config_site('hp_report');
        
        $email = config_site('email_report');
        $str = "Laporan ".date("D-m-Y")."<hr/>";
        $acc = $this->account_model->all_member(' email ');
        $str.="total account berdasarkan email:" . number_format(count($acc));
        $message = "total account (email):" . number_format(count($acc));

        $acc = $this->users_model->total();
        $str.="\ntotal user:" . number_format($acc);
        $message.="\ntotal Users:" . ($acc);

        $data = array('email', array('cek_only' => TRUE));
        $res = _localApi('datatable', 'execute', $data);
        $str.="\ntotal Email Send:" . $res['data']['recordsTotal'];

        $data = array('email_logs', array('cek_only' => TRUE));
        $res = driver_run($driver_core = 'advforex', $driver_name = 'email_logs', $func_name = 'total');
        //$str.=print_r($res,1);//"\ntotal SMS Send:".$res['data']['recordsTotal'];
        $str.="\ntotal Email:" . $res[0];
        $str.="\nreport send<hr/><table border=2>"
                . "<tr><th>jumlah</th><th colspan=2>tanggal dan waktu</th></tr>";
        foreach ($res[1] as $row) {
            $str.="<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
        }
        $str.="</table>";


        $data = array('sms', array('cek_only' => TRUE));
        $res = _localApi('datatable', 'execute', $data);
        $str.="\ntotal SMS Send:" . $res['data']['recordsTotal'];
        //;print_r($res,1);//
        $data = array('sms_logs', array('cek_only' => TRUE));
        $res = driver_run($driver_core = 'advforex', $driver_name = 'sms_logs', $func_name = 'total');
        //$str.=print_r($res,1);//"\ntotal SMS Send:".$res['data']['recordsTotal'];
        $str.="\ntotal SMS:" . $res[0];
        $str.="\nreport send<hr/><table border=2>"
                . "<tr><th>jumlah</th><th>tanggal</th><th>kegiatan</th></tr>";
        foreach ($res[1] as $row) {
            $str.="<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
        }
        $str.="</table>";

        $str.="\nUser terbaru:\n\n<table border=1>";
        //$acc = $this->users_model->gets_all('gundambison@gmail.com');
        //echo_r($acc);
        $filter['show_fields']='u_id,u_email,ut_name,u_modified ';
        $acc = $this->users_model->get_data($filter,30);//10, 0, 'accountid, email,created,agent', 'id', 'desc');
        //echo_r($acc);

        //$str .=print_r($acc,1);
        foreach ($acc as $row) {
            $str.="<tr>";
            $str.="<td>" . implode("</td> <td>", $row) . "</td>";
            $str.="</tr>";
        }
        $str.="</table>";
        
        $str.="\nAkun terbaru:\n\n<table border=1>";
        $acc = $this->account_model->all(10, 0, 'accountid, email,created,agent', 'id', 'desc');

        //$str .=print_r($acc,1);
        foreach ($acc as $row) {
            $str.="<tr>";
            $str.="<td>" . implode("</td> <td>", $row) . "</td>";
            $str.="</tr>";
        }
        $str.="</table>";
        
         $params = array(
            'debug' => true,
            'number' => $hp_send,
            'message' => $message,
            'header' => "debug",
                //    'local'=>true,
                //    'type'=>'masking'
        );
        
        $respon = is_local()?array():smsSend($params);
        
        $str.="<hr>sms send:".print_r($respon,1);
        
        $run = is_local()?FALSE:batchEmail($email, 'Reporting Salmamarkets', nl2br($str) );
        logCreate($run, 'sms');
        
       
        //$time[]=  microtime();
        logCreate($respon, 'sms');
        echo is_local()?"$hp_send $email<pre>" . $str:die('done');
    }

    function check_account($accountid) {
        $urls = ciConfig('apiForex_url');
        $url = $urls['get_account'];
        echo "\n$url";
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid
        );
        $res = _runApi($url, $params);
        print_r($res);
        if ($res['AgentID'] != '0' && $res['AgentID'] != '') {
            $sql = "UPDATE `mujur_account` SET `agent` = '$res[AgentID]' where accountid='$accountid';";
            dbQuery($sql);
        }
    }

    function send_msg_to_member() {

        $this->load->model('account_model');
        $acc = $this->account_model->all_member(' email ');
        echo "total:" . count($acc) . "\n";
        foreach ($acc as $row) {
            $email = strtolower($row['email']);

            if ($email != '')
                $member_email[$email] = 1;

            $this->send_msg_member($email);
            die;
        }
    }

    function all_email() {
        $data = $this->forex_model->emailData(10);
        $result = array();
        foreach ($data as $row) {
            $id = $row['id'];
            $to = $row['to'];
            $subject = $row['subject'];
            $message = $row['messages'];
            $headers = $row['headers'];
            //	echo "\n: $id $to";
            //	batchEmail( $to , $subject , $message , $headers, false);
            $this->forex_model->emailHide($row['id']);
            $result[] = array($to, $subject, $message, $headers);
            log_info_table('email', array($to, $subject));
            //	$result[]=array(array_keys($row),$row['status'] );
        }
        echo json_encode($result);
    }

    function send_msg_member($email) {
        $user = $this->users_model->getDetail($email);
        print_r($user);
        $phone = $user['phone'];
        $account = $this->account_model->get_by_email($email);
        print_r($account);
        $account_agent = $account_member = array();
        foreach ($account as $row) {
            if ($row['type'] == 'AGENT') {
                $account_agent[] = $row['accountid'];
            } else {
                $account_member[] = $row['accountid'];
            }
        }

        if (count($account_agent) != 0) {
            echo "\n=" . count($account_agent);
            return false; //sudah di kirim
        }
        $data = array(
            'username' => $email,
            'account' => $account,
            'phone' => $phone,
                // 'allow_sms'=>true,
        );
        $str = $this->load->view('email/email_info_member', $data, true);
        die($str);
        print_r($data);
    }

    function send_msg_to_agent() {
        //exit;
        $this->load->model('account_model');
        $agent = $this->account_model->all_agent(' email ');
        $agent_email = array();
        foreach ($agent as $row) {
            $email = strtolower($row['email']);

            if ($email != '')
                $agent_email[$email] = 1;
        }
        ksort($agent_email);
        print_r($agent_email);
        foreach (array_keys($agent_email) as $email) {
            $user = $this->users_model->getDetail($email);
            //print_r($user );
            $phone = $user['phone'];
            $account = $this->account_model->get_by_email($email);
            //print_r($account);
            $data = array(
                'username' => $email,
                'account' => $account,
                'phone' => $phone
            );
            $str = $this->load->view('email/email_info_agent', $data, true);
            //die($str);
            //echo $str;
            echo "\n$email\ttotal:" . count($account);
            if (count($account) > 2)
                die($str);
            batchEmail($email, 'Account List for Agent', $str);
        }
    }

    function list_user_under_agent() {
        $file = "tmp/account.csv";
        //die('1');
        $row = 1;
        $agent = array();
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 3000, ";")) !== FALSE) {
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                /*

                 */
                $pos = 15;
                $key = trim($data[15]);
                if ($key != (int) $key) {
                    $key = trim($data[16]);
                    $pos = 16;
                }

                if ($key == '1 : 1000') {
                    $pos++;
                    $key = trim($data[$pos]);
                    //echo $pos;die($key);
                }

                if ($key == '1 : 200') {
                    $pos++;
                    $key = trim($data[$pos]);
                    //echo $pos;die($key);
                }

                if ($key != 0) {
                    //echo "\n$agent\t{$data[0]}\t".$data[9];
                    //$agent++;


                    if ($key != (int) $key) {
                        for ($c = 0; $c < $num; $c++) {
                            echo $c . "\t->\t" . $data[$c] . "\n";
                        }
                        die();
                    }
                    //$agent[ $key ]=isset($agent[ $key ])?$agent[ $key ]:0;
                    //$agent[ $key ]++;
                    for ($c = 0; $c < $num; $c++) {
                        //    echo $c."\t->\t".$data[$c] . "\n";
                    }
                    $member[$data[0]] = $key;
                    //die($key);
                } else {

                    //    echo "\n$row not agent ".$data[15];
                }
                //if($row>53)exit;
                //echo "\n\n";
            }
            fclose($handle);
        } else {
            echo "error?";
        }
        ksort($member);
        //echo "\ntotal:".count($member)."\n".print_r($member,1)."\n";die;
        foreach ($member as $kode => $agent) {
            $str = "dibawah agent:" . $agent; //,`detail`='$str'
            $sql = "UPDATE `mujur_account` SET `agent` = '$agent' where accountid='$kode';";
            echo "\n" . $sql;
        }
        //echo "OK";
    }

    function list_agent() {
        $file = "tmp/account.csv";
        $row = 1;
        $agent = array();
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 3000, ";")) !== FALSE) {
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                /*
                  for ($c=0; $c < $num; $c++) {
                  echo $c."\t->\t".$data[$c] . "\n";
                  }
                 */
                $pos = 15;
                $key = trim($data[15]);
                if ($key != (int) $key) {
                    $key = trim($data[16]);
                    $pos = 16;
                }

                if ($key == '1 : 1000') {
                    $pos++;
                    $key = trim($data[$pos]);
                    //echo $pos;die($key);
                }

                if ($key == '1 : 200') {
                    $pos++;
                    $key = trim($data[$pos]);
                    //echo $pos;die($key);
                }

                if ($key != 0) {
                    //echo "\n$agent\t{$data[0]}\t".$data[9];
                    //$agent++;


                    if ($key != (int) $key) {
                        for ($c = 0; $c < $num; $c++) {
                            echo $c . "\t->\t" . $data[$c] . "\n";
                        }
                        die();
                    }
                    $agent[$key] = isset($agent[$key]) ? $agent[$key] : 0;
                    $agent[$key] ++;
                } else {
                    //    echo "\n$row not agent ".$data[15];
                }
                //if($row>53)exit;
                //echo "\n\n";
            }
            fclose($handle);
        } else {
            echo "error?";
        }
        ksort($agent);
        echo "\ntotal:" . count($agent) . "\n" . print_r($agent, 1) . "\n";
        foreach ($agent as $kode => $total) {
            $str = "total dibawah agent:" . $total;
            $sql = "UPDATE `mujur_account` SET `type` = 'AGENT',`detail`='$str' where accountid='$kode';";
            echo "\n" . $sql;
        }
    }

    //put your code here
    function __CONSTRUCT() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('account_model');
    }

    function parse_account() {
        $str = '  
{"reg_id":true,"username":"Sani Yulianti","investorpassword":"c817aa4e52112c3ead8d1941efbd9919","masterpassword":"d38374d8af0e2aab9b72db6093806664","accountid":"7902417","email":"saniyulianti1976@gmail.com","created":"2017-10-07","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Sani Yulianti","investorpassword":"03417e65cd258bcb8ca78e4f404af53e","masterpassword":"39774ac398b1a8ea9287a931761a8b93","accountid":"7902418","email":"saniyulianti1976@gmail.com","created":"2017-10-07","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Muhammad Aris Sayuti","investorpassword":"6559db07a8b71c18d5a5a9625ddd116b","masterpassword":"be687c3712d980d07eb46695fdb0d162","accountid":"7902419","email":"arizsayuti86@gmail.com","created":"2017-10-07","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"syahrir efrizal","investorpassword":"02628e27cb628fdd9c1ee0239c2f649f","masterpassword":"36dd470f8206bbe25c37596417087378","accountid":"7902420","email":"syahrir.e@yahoo.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Junianto","investorpassword":"a6bcc8637349544e9d64fdd554736397","masterpassword":"e71d0e3136b2a35ff32e9fd1f22afb19","accountid":"7902421","email":"anto.century@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
 {"id":true,"reg_id":true,"username":"Mustain","investorpassword":"90594603e0df82505aa8309e0bb7292d","masterpassword":"5830ee4788015abfbd7386525619a24f","accountid":"7902422","email":"mustain416@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
 {"id":true,"reg_id":true,"username":"siti cholifah","investorpassword":"b93def9cddad350bdd49eca30e21f8ea","masterpassword":"d1cf814dfc283d5ba6247a2e29e73214","accountid":"7902423","email":"lifahfx@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
 {"id":true,"reg_id":true,"username":"siti cholifah","investorpassword":"b61039263642a6152f7ff02abcd3a029","masterpassword":"22680af352f3a6e53dbc801d3e045917","accountid":"7902424","email":"lifahfx@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Muhammad yuzmir shafiq bin mohemad shukri Apit","investorpassword":"2aaebddeb8f240ebe5306cde4904bb6d","masterpassword":"bda4415f4408753a3c9cc9752a9b4e61","accountid":"7902425","email":"Yuzmir1990@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Amril alidar","investorpassword":"02477a1ca5edb2c2ab9fccfe2780e3b5","masterpassword":"f3c52464ce63d888b88017766866a4ba","accountid":"7902426","email":"aalidar@yahoo.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Maulana Hasan","investorpassword":"4375cf76e6855ad5a35bbcf1aa865ea6","masterpassword":"fa37c5dc96e36eab14d18998bb3a214e","accountid":"7902427","email":"maulanahasan388@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Maulana Hasan","investorpassword":"a77c3c9bc1417cab4381949e821279fd","masterpassword":"50475c20467456f80838133f17d50865","accountid":"7902428","email":"maulanahasan388@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"gusti putu ari dananjaya","investorpassword":"77fb8ebddc09b8ad2f14e68958d4a5f7","masterpassword":"2b5a240469c4575c1070e90a8e9b09ab","accountid":"7902429","email":"belon.cruise@yahoo.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"hari susanto","investorpassword":"17da55aabb15b88591b784efe660555e","masterpassword":"b69456120ca3863f092327badacd27bd","accountid":"7902430","email":"harisusanto1989@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Prasetyo widodo","investorpassword":"db82c6e0ff60ab4472739291c790b00d","masterpassword":"4e33558184a3682ba23381413d50cd38","accountid":"7902431","email":"Riyanprasetyo09@gmail.co,","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Sutikno Sofjan","investorpassword":"85ae0598cf0606f54315fff28b173ec8","masterpassword":"417fc97fd126f10e5208c4e49f42d199","accountid":"7902432","email":"sutikno.sofjan@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Riska","investorpassword":"3109493f6b5b63d459212eba88c851fc","masterpassword":"085d5547498007975e4b1463a07acbeb","accountid":"7902433","email":"riska1977@yahoo.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"hari susanto","investorpassword":"2c1b45810eda7a647940af0b21a35735","masterpassword":"e802637bd2e367d14879776cf93f8bd8","accountid":"7902434","email":"harisusanto1989@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"hari susanto","investorpassword":"60b6387a4d335f9378fdf7576cfb28a4","masterpassword":"d4831b1883696b2f2920c7e719505fd6","accountid":"7902435","email":"harisusanto1989@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"dodi lati ajisaputra","investorpassword":"942158c226fc0ff84ba8c48098a17567","masterpassword":"3480b4d8c63e30a0e66f57d30f102c99","accountid":"7902436","email":"dodilatiajisaputra@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"hari susanto","investorpassword":"7d24cbe5ad6102f7d4351758ab19956d","masterpassword":"426f747ca830d0dceccc6213e5faaf72","accountid":"7902437","email":"harisusanto1989@gmail.com","created":"2017-10-08","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Feri fadli","investorpassword":"6d864aaf6c60f478d85db7d4a4050c75","masterpassword":"46f49f15b556795e136abddcc3232f9c","accountid":"7902438","email":"Fery1407@gmail.com","created":"2017-10-09","agent":null,"type":"MEMBER"}
{"id":true,"reg_id":true,"username":"Jamaludin yahya","investorpassword":"15409c9aeda42473ab6d44d3930db052","masterpassword":"9a31eb2840457c1c9748453578a2512b","accountid":"7902416","email":"jamaludiny283@yahoo.com","created":"2017-10-07","agent":null,"type":"MEMBER"}
 ';

        echo "\n\n";
        $ar = explode("\n", trim($str));
        foreach ($ar as $v) {
            $ar2 = json_decode($v, true);

            unset($ar2['id']);
            $ar2['reg_id'] = dbId('regis');
            //print_r($ar2);
            $sql = "insert into mujur_account (";
            $sql.=implode(",", array_keys($ar2));
            $sql.=") vALUES ( '";
            $sql.=implode("',\n '", ($ar2));
            $sql.="');";
            echo $sql;
        }
        //INSERT INTO `mujur_account` (`id`, `username`, `email`, `created`, `modified`, `investorpassword`, `masterpassword`, `reg_id`, `accountid`, `status`, `agent`, `type`, `detail`) VALUES ('1610129069', '7902415', 'jamaludiny283@yahoo.com', '2017-10-01', CURRENT_TIMESTAMP, '', '', '1', '7902415', NULL, NULL, 'MEMBER', NULL);
    }

    function send_sms() {
        $post = $this->input->post();
        $hp_send = '628568132429';
        $message = 'hello ini test dari localhost';
        $header = 'dari luar';
        $params = array(
            'debug' => true,
            'number' => $hp_send,
            'message' => $message,
            'header' => $header,
                //    'local'=>true,
                //    'type'=>'masking'
        );

        $respon = smsSend($params);
        $time[] = microtime();
        logCreate($respon, 'sms');
        print_r($respon);
    }

}
