<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advforex_user_edit extends CI_Driver {
private $urls,$privatekey;
public $CI;
	function __CONSTRUCT(){
		$CI =& get_instance();
		$CI->load->helper('api');
		//$CI->config->load('forexConfig_new', TRUE);
		$this->urls = $urls=$CI->config->item('apiForex_url' );
		$this->privatekey = $CI->config->item('privatekey' );
		$CI->load->helper('formtable');
		$CI->load->helper('form');
	}

    function execute($param){
	$CI =& get_instance();
	ob_start();
	$CI->load->driver('advforex'); /*gunakan hanya bila diperlukan*/
		$driver_core = 'advforex';
		$func_name='detail';
	//$param1=array('post0'=>$param);
	$driver_name='user';
	$data=$CI->{$driver_core}->{$driver_name}->{$func_name}($param);
	$result=array('status'=>true);
	$detail=$data['user_detail'];
	$html='<pre>'.print_r($detail,1).'</pre>';//
	$html=form_open(site_url('admin/save_users')).'
	<h2>EDIT USER</h2><h3>'.$detail['email'].'</h3>
	<table>';
	$html.=form_hidden('email',$detail['email']);
	$html.=bsInput('name','detail[name]', $detail['name'] ,'full name');
	$html.=bsInput('country','detail[country]', $detail['country'] ,'country');
	$html.=bsInput('city','detail[city]', $detail['city'] ,'city');
	$html.=bsInput('state','detail[state]', $detail['state'] ,'state');
	$zipcode=isset($detail['zip'])?$detail['zip']:'';
	$zipcode=isset($detail['zipcode'])?$detail['zipcode']:$zipcode;
	
	$html.=bsInput('zipcode','detail[zipcode]', $zipcode ,'zip code');
	$html.=bsInput('address','detail[address]', $detail['address'] ,'-');
	$html.=bsInput('phone','detail[phone]', $detail['phone'] ,'-');
	$html.=bsInput('bank','detail[bank]', isset($detail['bank'])?$detail['bank']:'' ,'-');
	$html.=bsInput('rekening','detail[bank_norek]', isset($detail['bank_norek'])?$detail['bank_norek']:'' ,'-');
        
        $html.=bsInput('Master Code','mastercode', isset($detail['users']['u_mastercode'])?$detail['users']['u_mastercode']:'' ,'-');
        //$html.=bsInput('Currency','mastercode', isset($detail['users']['u_currency'])?$detail['users']['u_currency']:'' ,'-');
    //==============TYPE============
        $option = $CI->users_model->select_type_data();
        $html.= bsSelect('Type', 'type',$option, $detail['users']['u_type'] );
        
    //==============TYPE============
        $option = $CI->forex_model->select_currency();
        $html.= bsSelect('Currency', 'currency',$option, $detail['users']['u_currency'] );
        
        //bsInput('Type','mastercode', isset($detail['users']['u_type'])?$detail['users']['u_type']:'' ,'-');
        //form_dropdown('shirts', $options, 'large'); bsSelect($title, $name, $data='',$default='')
//================Tanggal lahir=============
	$dt = array(
                'name'          => '',
                'id'            => 'input_1',
                'value'         => '',
                'class'			=> 'form-control_2',
                'type'			=> 'text',
                'placeholder'	=> "tanggal bulan tahun",
                'style'	=> 'width:50px',
	);
	$inp='';
	$dt['name']='detail[dob1]';
	$dt['value']=isset($detail['dob1'])?$detail['dob1']:'';
	$inp.= form_input($dt);
	$dt['name']='detail[dob2]';
	$dt['value']=isset($detail['dob2'])?$detail['dob2']:'';
	$inp.= form_input($dt);
	$dt['name']='detail[dob3]';
	$dt['value']=isset($detail['dob3'])?$detail['dob3']:'';
	$inp.= form_input($dt);
	$html.='<tr><td>Birth</td><td>:</td><td>'.$inp.'</td></tr>';
	//$html.=bsInput('lastname','detail[lastname]', $detail['lastname'] ,'nama jelas');
	$html.=bsButton('save');
	$html.='</table></form>';
	$html.="<h2>PASSWORD</h2>".form_open(site_url('admin/update_password')).'<table>';
	$html.=form_hidden('email',$detail['email']);
	$html.=bsInput('password','password', '' ,'password');
	$html.=bsButton('update password');
	$html.='</table></form>';
	
	//$html.='<pre>'.print_r($detail,1).'</pre>';
	//$html.='<pre>'.print_r($param,1).'</pre>';
	$result['html']=$html;
	$html = ob_get_contents();
	ob_end_clean();
	//$result['warn']=explode("\n",$html);
	return $result;
    }

}
/*==*/