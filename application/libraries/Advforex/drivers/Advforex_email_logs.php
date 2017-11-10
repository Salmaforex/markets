<?php

//Advforex_forex_summary
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  Advforex_forex_balance
 */

class Advforex_email_logs extends CI_Driver {

    private $urls, $privatekey;
    public $CI;

    function execute($params) {
        $CI = & get_instance();
        $result = array('params' => $params);
        $result['time'][] = microtime(true);
        $post0 = isset($params['post0']) ? $params['post0'] : array();
        $times = array(microtime());
        //	return $result;
        ob_start();
        //api_data
        $result = array(
            'draw' => isset($post0['draw']) ? $post0['draw'] : 1
        );
        $aOrder = array(
            'created', 'username0', 'username', 'email'
        );
        $year = date('Y');
        $sql = "select count(*) c from (SELECT count(*) c FROM `zbatch_email` group by  date(created), hour(created)) "
                . "as c";
        $dt = dbFetchOne($sql);
        $result['time'][] = microtime(true);
        $result['sql'][] = array($sql, $dt);
        //$result['db_res'][]=$dt;
        $times['count all'] = microtime();
        $result['recordsTotal'] = $dt['c'];
        $result['recordsFiltered'] = $dt['c']; //karena tidak ada filter?!

        $start = isset($post0['start']) ? $post0['start'] : 0;
        $limit = isset($post0['length']) ? $post0['length'] : 2;
        $data = array();
        $orders = "order by id desc";

        if (isset($post0['order'][0])) {
            $col = $post0['order'][0]['column'];
            $order = $post0['order'][0]['dir'];
            $col2 = $post0['columns'][$col]['data'];

            if ($col == 0) {
                $col2 = 'tgl';
                //	$order='desc';
            }
            if ($col == 3) {
                $col2 = 'tmp6';
            }
            if ($col == 5) {
                $col2 = 'tmp7';
            }

            $orders = "order by {$col2} {$order}";
        }

        $where = '1';
        $search = isset($post0['search']['value']) ? $post0['search']['value'] : '';

        if ($search != '' && strlen($search) > 3) {
            $where = "tmp6 like '%{$search}%'";
            //$where.="or ud.ud_detail like '{$search}%'";
            $sql = "select count(id) c from `zbatch_email`			
			where  ($where) ";
            /*
              left join mujur_accountdetail ad
              on a.username=ad.username
             */
            $res = dbFetchOne($sql, 1);
            $result['time'][] = microtime(true);
            $times['count Filtered'] = microtime();
            $result['sql'][] = $sql;
            $result['recordsFiltered'] = $res['c'];
        } else {
            $times['count no search'] = microtime();
            logCreate('no search :' . $search);
        }

        $where2 = ''; //' and u_email like "gundambison%" ';
        $sql = "select count(*) jumlah, date(created) tgl,  hour(created) as hour from 
                    `zbatch_email`
                    group by date(created), hour(created)
                      $orders limit $start, $limit
                    
                   ";
        /*
         * 		group by u_email
          left join mujur_accountdetail ad
          on a.username=ad.username
         */
        //
        $result['sql'][] = $sql;
        $data = array();
        logCreate('sql :' . $sql);

        $dt = dbFetch($sql);
        $result['time'][] = microtime(true);
        $times['run query'] = microtime();
        logCreate('total :' . count($data));
        foreach ($dt as $row) {
            $result['time'][] = microtime(true);
            $row['status'] = $row['hour'] . ":00";
            $data[] = $row;
        }

        $result['data'] = $data;
        $result['times'] = $times;
        unset($result['time']);
        $result['params'] = $params;
        $warning = ob_get_contents();
        ob_end_clean();

        if ($warning != '') {
            $result['warning'] = $warning;
        }

        return $result;
    }

    function __CONSTRUCT() {
        $CI = & get_instance();
        $CI->load->helper('api');
        $CI->load->model('users_model');
        //$CI->config->load('forexConfig_new', TRUE);
        $this->urls = $urls = $CI->config->item('apiForex_url');
        $this->privatekey = $CI->config->item('privatekey');
    }
    
    function total($params){
        $sql= "select count(id) total from zbatch_email";
        $total= dbFetchOne($sql)['total'];
        $sql = "select count(id) jumlah, date(created) tgl,  hour(created) as hour from 
                    `zbatch_email`
                    group by date(created), hour(created) 
                    order by `tgl` desc
                    limit 50
                    ";
        $dt = dbFetch($sql);
        return array($total,$dt);
        
    }
}
