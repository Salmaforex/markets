<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* basic helper */
function ciConfig($name='' ){
	$CI =& get_instance();
	$config = $CI->config->item($name);
	return $config;
}

function echo_r($param=array()){
	if(defined('LOCAL')){
	echo '<pre>'.print_r($param,1).'</pre>';
	}
	else{
		logCreate('param |echo_r |total param='.count($param) );
	}
}

function js_goto($url) {
	logCreate('js |goto |url='.$url);
    echo "<script>window.location.href = '{$url}';</script>";
	log_info_table('redirect',array($url));
	exit();
}

if ( ! function_exists('callback_submit')){
	function callback_submit(){
    $CI =& get_instance();
    $notif=$CI->session->flashdata('notif');
    if($notif){
/*	echo '
	    <div class="note '.($notif['status'] ? 'note-success' : 'note-danger').' note-shadow">
		    <button class="close" data-close="alert"></button>
		    <p>'.$notif['msg'].'</p>
	    </div>
	';
*/
	echo '<div style="margin:20px 40px" class="alert alert-block '.($notif['status'] ? 'alert-success' : 'alert-danger').' fade in">
          <button data-dismiss="alert" class="close" type="button"> × </button>
          <h4 class="alert-heading"><i class="fa fa-times"></i>'.($notif['status'] ? 'Information' : 'Notice').'!</h4>
          <p>'.$notif['msg'].'</p>
        </div>';
	//echo_r($notif);
    }
	}
} else{}

if( ! function_exists('driver_run')){
	function driver_run($driver_core, $driver_name, $func_name='executed', $params=array()){
		$result=array('code'=>0,'data'=>false,'messages'=>'');
		log_info_table('driver',array($driver_core, $driver_name, $func_name));

/* no drivers core =============================== */
		$core_file=ucfirst(strtolower($driver_core));
		if(!is_file(APPPATH.'libraries/'.$core_file.'/'.$core_file.".php")){
			$result['messages']=!defined('LOCAL')?'no core driver file':'buatlah core drivernya di:'.APPPATH.'libraries/'.$core_file.'/'.$core_file.".php";
			$result['error']=100;
			return $result;
		}

		$CI =& get_instance();
		$CI->load->driver($driver_core);
		
		// log_add("run driver: $driver_core| $driver_name| $func_name");
		// log_add("parameter:".count($params));
/*	Kita butuh file config khusus untuk daftar driver  */
		$config_file='driver_gw';
		if(!is_file(APPPATH.'config/'.$config_file.".php")){
			// log_add('buatlah confignya di:'.APPPATH.'config/'.$config_file.".php",'error');
			$result['messages']=!defined('LOCAL')?'no config file':'buatlah confignya di:'.APPPATH.'config/'.$config_file.".php\nbuatlah array confignya \$config['drivers_{$driver_core}']=array('{$driver_name}');" ;
			$result['error']=101;
			return $result;
		}

/*	Kita butuh config parameter untuk daftar driver  */
		$CI->config->load($config_file);
		$valid_drivers= $CI->config->item('drivers_'.$driver_core);
		if(is_null($valid_drivers)||$valid_drivers===false){
			// log_add("buatlah array confignya \$config['drivers_{$driver_core}']=array();",'error');
			$result['error']=102;
			$result['messages']=!defined('LOCAL')?'no config':"buatlah array confignya \$config['drivers_{$driver_core}']=array();" ;
			return $result;
		}
/*	Kita butuh nilai parameter yang sesuai untuk daftar driver  */		
		if(!in_array($driver_name,$valid_drivers)){
			// log_add("buatlah nilai '{$driver_name}' pada array confignya \$config['drivers_{$driver_core}']=array('{$driver_name}');",'error');
			$result['error']=103;
			$result['messages']=!defined('LOCAL')?'no config': "buatlah nilai '{$driver_name}' pada array confignya \$config['drivers_{$driver_core}']=array('{$driver_name}');" ;
			return $result;
		}
		
/* keberadaan file driver ====================================  */
		$core_file=ucfirst(strtolower($driver_core));
		$driver_file=ucfirst(strtolower($driver_core))."_".strtolower($driver_name);
		if(!is_file(APPPATH.'libraries/'.$core_file.'/drivers/'.$driver_file.".php")){
			// log_add('buatlah file drivernya di:'.APPPATH.'libraries/'.$core_file.'/drivers/'.$driver_file.".php",'error');
			$result['error']=104;
			$result['messages']=!defined('LOCAL')?'no config':  'buatlah file drivernya di:'.APPPATH.'libraries/'.$core_file.'/drivers/'.$driver_file.".php" ;
			return $result;
		}

/*	Kita butuh functionnya ==================================  */
		if( !method_exists($CI->{$driver_core}->{$driver_name}, $func_name) ){
			// log_add('buatlah fungsi '.$func_name.'($params) pada file drivernya di:'.APPPATH.'libraries/'.$core_file.'/drivers/'.$driver_file.".php",'error');
			$result['error']=105;
			$result['messages']=!defined('LOCAL')?'no config': 'buatlah fungsi '.$func_name.'($params) pada file drivernya di:'.APPPATH.'libraries/'.$core_file.'/drivers/'.$driver_file.".php" ;
			return $result;
		}
		else{
			$result['error']=false;
			$result['messages']='success';
			$result['data']=$CI->{$driver_core}->{$driver_name}->{$func_name}($params);
		}
		
		return isset($result['data'])?$result['data']:array();
	}
	
}

function rnd_word($j){
    $word='zxcvbnmasdfghjklqwertyuiop1234567890QWERTYUIOPASDFGHJKLMNBVCXZ';
    $words='';
    $n=strlen($word);
    for($i=0;$i<$j;$i++){
        $rand=  rand(10000, 90000);
        $pos = $rand % $n;
        $words.= substr($word, $pos,1);
    }
    
    return $words;
}
/*===================*/