<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Advforex_account extends CI_Driver {

    private $urls, $privatekey;
    public $CI;

    function __CONSTRUCT() {
        $CI = & get_instance();
        $CI->load->helper('api');
        //$CI->config->load('forexConfig_new', TRUE);
        $this->urls = $urls = $CI->config->item('apiForex_url');
        $this->privatekey = $CI->config->item('privatekey');
        $CI->load->model('account_model');
        $CI->load->model('forex_model');
        $CI->load->model('users_model');
    }

    function execute($raw_data) {
        $CI = & get_instance();
        /* ---------- */
        $post = $post0 = $raw_data['post0'];
        $times = array(microtime());
        ob_start();
        logCreate('start DATA = account');
        //api_data
        $respon = array(
            'raw' => $raw_data,
            'draw' => isset($post['draw']) ? $post['draw'] : 1);
        $aOrder = array(
            'created', 'name', 'email', 'accountid', 'agent', 'type'
        );

        $sql = "select count(a.id) c from `{$CI->account_model->table}` a 
		left join `{$CI->users_model->table}` u on a.email = u.u_email";
        $dt = dbFetchOne($sql);
        $respon['sql'][] = $sql;
        $times['count 0'] = microtime();
        //$this->db->query($sql)->row_array();
        $respon['recordsTotal'] = $dt['c'];
        $respon['recordsFiltered'] = $dt['c']; //karena tidak ada filter?!
        logCreate('respon:' . json_encode($respon));
        //$sql="select * from `{$CI->account_model->table}` a limit 1";
        //$respon['account']=dbFetchOne($sql);
        //return $respon;

        $start = isset($post0['start']) ? $post0['start'] : 0;
        $limit = isset($post0['length']) ? $post0['length'] : 11;
        $data = array();
        $orders = "order by a.modified desc";
        if (isset($post0['order'][0])) {
            $col = $post0['order'][0]['column'];
            $order = $post0['order'][0]['dir'];
            $col2 = $post0['columns'][$col]['data'];
            if ($col == 0) {
                $col2 = 'a.modified';
            }
            if ($col == 2) {
                $col2 = 'a.email';
            }
            if ($col == 3) {
                $col2 = 'a.accountid';
            }

            $orders = "order by {$col2} {$order}, created asc";
        }
        $where = '1';
        $search = isset($post0['search']['value']) ? $post0['search']['value'] : '';
        if ($search != '' && strlen($search) > 2) {
            $where = "a.email like '{$search}%'";
            //$where.=" or a.email like '{$search}%'";
            //$where.=" or ad.detail like '%{$search}%'";
            $sql = "select count(id) a from `{$CI->account_model->table}` a 
		left join `{$CI->users_model->table}` u on a.email = u.u_email
			where $where";

            $res = dbFetchOne($sql, 1);
            $times['count 2a'] = microtime();
            $respon['sql'][] = $sql;
            $respon['recordsFiltered'] = $res['c'];
            logCreate('respon:' . json_encode($respon));
        } else {
            logCreate('no search :' . $search);
            $times['count 2b'] = microtime();
        }

        $sql = "select a.id, a.modified created, a.username name, a.email main_email,a.accountid,a.agent,a.type, '-'
		from `{$CI->account_model->table}` a 
		left join `{$CI->users_model->table}` u on a.email = u.u_email
		where $where
			$orders
		limit $start,$limit";
        /*
          left join mujur_accountdetail ad
          on a.username=ad.username
         */
        logCreate('sql :' . $sql);
        $respon['sql'][] = $sql;
        $dt = dbFetch($sql, 0);
        $times['query'] = microtime();
        //$this->db->query($sql)->result_array();
        logCreate('total user approval:' . count($dt)); //exit();
        foreach ($dt as $num => $row) {
            logCreate('search :' . $row['id']);
            $row['username'] = '';
            //	var_dump($row);exit();
            $account = $CI->account_model->gets($row['id'], 'id', true);
            $user = $CI->users_model->gets($row['main_email'], 'u_email');
            $detail = $CI->users_model->getDetail($row['main_email'], 'ud_email', true);
            $row['raw'] = array(
                'account' => $account,
                'user' => $user,
                'user_detail' => $detail
            );
            $row['username'] = isset($detail['name']) ? $detail['name'] : 'user forex';
            $times['cari ' . $row['id'] . '| email_' . $row['main_email']] = microtime();
            //echo_r($detail);exit();
            //detail($row['id']);
            $row['name'] = isset($row['name']) ? $row['name'] : 'user forex';
            $row['modified'] = isset($detail['user']['u_modified']) ? $detail['user']['u_modified'] : '-';

            //unset($detail['raw']);
            //foreach($detail as $nm=>$val){ $row[$nm]=$val; }

            $row['action'] = '';
            $row['email'] = $row['main_email'] . ".";
            $data[] = $row;
        }

        $respon['data'] = $data;
        $respon['-'] = $post0;
        $warning = ob_get_contents();
        ob_end_clean();
        if ($warning != '') {
            $respon['warning'] = $warning;
        }
        logCreate('times advforex user execute:' . json_encode($times));
        if (isset($respon)) {
            $respon['times'] = $times;
            if (!defined('LOCAL')) {
                unset($respon['raw'], $respon['sql'], $respon['-'], $respon['valid']);
            }
            return $respon;
        } else {
            return array();
        }
    }

}
