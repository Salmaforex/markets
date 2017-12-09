<?php

//Advforex_forex_summary
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Advforex_forex_update_balance_credit extends CI_Driver {

    private $urls, $privatekey;
    public $CI;

    function execute($row) {
        $CI = & get_instance();
        logCreate('start Advforex_forex_update_balance_credit');
        $post = isset($row['post0']) ? $row['post0'] : array();
        $result = array('post' => $post);
        $id = isset($post['id']) ? $post['id'] : false;
        if ($id === false) {
            logCreate('no id');
            return false;
        }

        $status = isset($post['status']) ? $post['status'] : false;
        if ($status === false) {
            logCreate('no status');
            return false;
        }

        //============cancel
        if ($status == 'cancel') {
            $status = -1;
            $str = 'dihapus pada :' . date("d-m-Y H:i:s");
            $result['end'] = array($id, $status, $str);
            $CI->forex_model->flow_data_update($id, $status, $str);
            logCreate($str);
            $this->send_email($id);
            return $result;
        }

        $data = $CI->forex_model->flow_data($id);
        $result['flow data'] = $data;
        //	logCreate('data:'.print_r($data,1));return array();
        $accountid = isset($data['detail']['account']) ? $data['detail']['account'] : false;
        $volume = 0;
        if (isset($data['types']) && $data['types'] == 'deposit') {
            logCreate('status deposit + ' . $accountid);
            $volume = $data['detail']['orderDeposit'];
            $operator = "+";
        } elseif (isset($data['types']) && $data['types'] == 'widtdrawal') {
            logCreate('status widtdrawal - ' . $accountid);
            $volume = $data['detail']['orderWidtdrawal'];
            $operator = "-";
        } else {
            logCreate('unknown data?' . $accountid . "\t" . json_encode($data));
            return false;
        }


        if ($accountid === false) {
            logCreate('accountid not valid');
            $accountid = isset($data['detail']['accountid']) ? $data['detail']['accountid'] : false;
        }
        logCreate('accountid:' . $accountid);

        $param2 = array(
            $accountid,
            trim($volume),
            $operator
        );

        logCreate('param:' . json_encode($param2));
        $result['balance'] = $balance = $this->balance($param2);
        $str = '';
        $status = false;
        $title = 'update balance';
        logCreate('advforex_forex_update_balance balance(1):' . json_encode($balance));

        if ($balance === true) {
            $status = 1;
            $str = 'balance success, ';
            $result['credit'] = $balance = $this->credit($param2);
            logCreate('advforex_forex_update_balance credit balance(2):' . json_encode($balance));
            if ($balance === true) {
                //$status=0;
                $str.='credit success';
            } else {
                $str.='credit not success';
                email_problem($title, "Last Messages " . $str, $balance);
                return false;
            }
        } else {
            $str = 'balance not success, ';
            if (isset($balance['raw']['ResponseCode']) && $balance['raw']['ResponseCode'] == 2 && $balance['raw']['ResponseText'] == 'Not enough money...') {
                logCreate('advforex_forex_update_balance |execute |ResponseCode:2 ' . json_encode($balance));
                return false;
            } elseif (isset($balance['raw']['ResponseCode']) && $balance['raw']['ResponseCode'] != 4) {
                logCreate('advforex_forex_update_balance |execute |ResponseCode:? ' . json_encode($balance));
                email_problem($title, "Last Messages : " . $str, $balance);
                return false;
            } else {
                logCreate('advforex_forex_update_balance |execute |ResponseCode:4 ' . json_encode($balance));
                return false;
            }
        }

        logCreate('info akhir:' . $str);
        $result['end'] = array($id, $status, $str);
        $CI->forex_model->flow_data_update($id, $status, $str);
        $this->send_email($id);

        return $result;
    }

    function execute_old($row) {
        $accountid = $row[0];
        $result = array();
        //$result[]=$row;
        $result[] = $urls = ciConfig('apiForex_url');
        $url = $urls['get_margin'];
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid
        );
        if (defined('LOCAL')) {
            $json = '{"AccountID":"' . $accountid . '", "Balance":"0.000000","Credit":"0.000000","Equity":"0.000000","FreeMargin":"0.000000","ResponseCode":"0","ResponseText":"Get margin success"}';
            $result['margin'] = json_decode($json, true);
        } else {
            $res = _runApi($url, $params);
            $result['raw'] = $res;
            if ((int) $res['ResponseCode'] == 0) {
                $result = array('margin' => $res);
            } else {
                $result = array('margin' => array(), 'raw' => $res);
            }
        }
        return $result;
    }

    function __CONSTRUCT() {
        $CI = & get_instance();
        $CI->load->helper('api');
        $CI->load->model('forex_model');
        //$CI->config->load('forexConfig_new', TRUE);
        $this->urls = $urls = $CI->config->item('apiForex_url');
        $this->privatekey = $CI->config->item('privatekey');
    }

    function balance($row) {
        logCreate('start balance:' . json_encode($row));
        $accountid = $row[0];
        $volume0 = $row[1];
        $volume = number_format($volume0, 2, ".", "");
        $operator = $row[2];

        //$result[]=
        $urls = ciConfig('apiForex_url');
        $url = $urls['updatebalance'];
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid,
            'volume' => trim($operator . $volume),
            'description' => '-'
        );
        if ($operator == '-') {
            $params['description'] = 'Withdrawal ' . number_format($volume0, 2, '.', '') . ' ' . date("H:i:s");
        } else {
            $params['description'] = 'Deposit ' . number_format($volume0, 2, '.', '') . ' ' . date("H:i:s");
        }

        $params['description'] = substr($params['description'], 0, 30);
        //$param['description']	= 	'Deposit '.$vol.' '.date("H:i:s");
        $result[] = array($url, $params);
        if (defined('LOCAL')) {
            logCreate('Advforex_forex_update_balance_credit |balance LOCAL. fix to true');
            $result['raw'] = 'local';
            return $result;
        }

        logCreate('Advforex_forex_update_balance_credit |balance |params:' . json_encode($params));
        $res = _runApi($url, $params);
        $result['raw'] = $res;

        if (!isset($res['ResponseCode'])) {
            $debug = array(
                'result' => $res,
                'params' => $params,
                'row' => $row
            );
            email_problem("API response unknown", "target:" . $url, $debug);
            logCreate('Advforex_forex_update_balance_credit |balance |api |error:' . json_encode($res));

            return $result;
        }

        if ((int) $res['ResponseCode'] == 0) {
            logCreate('Advforex_forex_update_balance_credit |balance |success');
            return true;
        } else {
            $debug = array(
                'result' => $res,
                'params' => $params,
                'row' => $row
            );

            if ((int) $res['ResponseCode'] == 2) {
                logCreate('advforex_forex_update_balance |balance ResponseCode:2 ' . json_encode($res));
                //return false;
            } elseif ((int) $res['ResponseCode'] != 4) {
                email_problem("API response balance", "target:" . $url, $debug);
                logCreate('Advforex_forex_update_balance_credit |balance |status:' . json_encode($res));
            } else {
                logCreate('Advforex_forex_update_balance_credit |balance not enough money (4) |debug:' . json_encode($debug));
            }
        }

        return $result;
    }

    function credit($row) {
        logCreate('start credit:' . json_encode($row));
        $accountid = $row[0];
        $volume = ($row[1] / 2);
        $operator = $row[2];
        if ($operator == '-') {
            logCreate('Advforex_forex_update_balance_credit |no update credit!');
            return true;
            //	return array('no credits'); 
            /* proses withdrawal tidak mengurangi credit */
        }
        //$result[]=
        $urls = ciConfig('apiForex_url');
        $url = $urls['updatecredit'];
        $params = array(
            'privatekey' => ciConfig('privatekey'),
            'accountid' => $accountid,
            'volume' => $operator . $volume,
            'description' => 'Credit ' . $operator . $volume . ' ' . date("H:i:s")
        );
        $result[] = array($url, $params);
        $params2 = $params;
        $params2['privatekey'] = '-';
        logCreate('Advforex_forex_update_balance_credit |params:' . json_encode($params2));
//Description
        $params['description'] = 'Credit ' . $operator . number_format($volume, 2, '.', '') . ' ' . date("H:i:s");
        //'Withdrawal '.number_format($volume,2, '.', '').' '.date("H:i:s");

        logCreate('Advforex_forex_update_balance_credit |acc:' . $accountid . ' |credit:' . $operator . $volume);
        if (defined('LOCAL')) {
            logCreate('Advforex_forex_update_balance_credit |credit LOCAL. fix to true');
            return true;
        }

        $res = _runApi($url, $params);
        $result['raw'] = $res;
        if (!isset($res['ResponseCode'])) {
            logCreate('Advforex_forex_update_balance_credit |credit |acc:' . $accountid . ' |api |error:' . json_encode($res));
            $debug = array(
                'result' => $res,
                'params' => $params,
                'row' => $row
            );
            email_problem("API response credit unknown", "target:" . $url, $debug);
            return $result;
        }

        if ((int) $res['ResponseCode'] == 0) {
            logCreate('Advforex_forex_update_balance_credit |credit |acc:' . $accountid . ' |api |success');
            return true;
        } else {
            $debug = array(
                'result' => $res,
                'params' => $params,
                'row' => $row
            );
            email_problem("API response credit", "target:" . $url, $debug);
            logCreate('Advforex_forex_update_balance_credit |credit |acc:' . $accountid . ' |status:' . $res['ResponseCode'] . ' |error:' . json_encode($res));
        }
        return $result;
    }

    function send_email($id) {
        $CI = & get_instance();
        $data = $CI->forex_model->flow_data($id);
        //	echo_r($data);
        $pesan = '';
        $email = $data['email'];
        if ($email == '') {
            $email = $data['detail']['username'];
        }



        if ($data['types'] == 'widtdrawal' || $data['types'] == 'withdrawal') {
            $pesan = $CI->load->view('email/email_withdrawal_status_view', $data, true);
            _send_email($email, '[salmamarkets] Confirmation Withdrawal (' . $data['detail']['account'] . ')', $pesan);
        }

        if ($data['types'] == 'deposit') {
            $pesan = $CI->load->view('email/email_deposit_status_view', $data, true);
            _send_email($email, '[salmamarkets] Confirmation Deposit (' . $data['detail']['account'] . ')', $pesan);
        }
        return $pesan;
    }

}
