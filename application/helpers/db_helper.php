<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
membutuhkan helper log 
work with basic db connection
*/
if ( ! function_exists('dbId')){
    function dbId($name="id",$start=10,$counter=1){
    $CI =& get_instance();
        $CI->load->dbforge();
        if($name=='')$name='id';
        if($name!='id'){
                $name.="_id";
        }else{}
        
        $name = 'id';
        
        if(!$CI->db->table_exists($name)){
            $CI->dbforge->add_field('id');
            $CI->dbforge->create_table($name,TRUE);
            $str = $CI->db->last_query();			 
            logConfig("create table:$str",'logDB');

        }else{}
        
        if (!$CI->db->field_exists('created', $name)){
            $sql="ALTER TABLE `{$name}` ADD `created` TIMESTAMP;";dbQuery($sql);
        }
        if (!$CI->db->field_exists('code', $name)){
            $sql="ALTER TABLE `{$name}` ADD `code` varchar(30);";dbQuery($sql);
            $sql="ALTER TABLE `{$name}` ADD index(`code`);";dbQuery($sql);
        }

        $now=date("Y-m-d H:i",strtotime("-5 minutes"));
        $sql="delete from `{$name}` where `created` < '$now';";dbQuery($sql);
        $sql="select count(id) c, max(id) max from $name";
        $data=dbFetchOne($sql);
        
        $start=date("ymdH1");

        //	$sql="ALTER TABLE `$name` CHANGE `id` `id` BIGINT  NOT NULL AUTO_INCREMENT;";
        //	dbQuery($sql);		

        if($data['c']==0){
            $data=array('id'=>$start);
            dbInsert($name, $data);
            //$sql = $CI->db->insert_string($name, $data);
            //dbQuery($sql);
            $num=$start;
        }
        else{
            $num=$data['max']+$counter;
            $num_min = (int) date("ymdHi");
            if($num < $num_min){
                $num=date("ymdHi");
            }
            
            $where="id=".$data['max'];
            $data=array('id'=>$num);
            $code='';
            $len = strlen($num);
            
            for($i=0;$i<=$len;$i+=5){
                $str = substr($num, $i,5);
                $code .=dechex($str);//."|".$str."-".$i;
            }
            //die($code."xx".$len);
            $data['code']=$code;
            ////$sql = $CI->db->update_string($name, $data, $where);
            //dbQuery($sql);
            dbInsert($name, $data);
            
        }

        //$str = $CI->db->last_query();
        //logConfig("dbId sql:$str",'logDB');

        //$CI->db->reset_query();
        return $num;
    }
    
}else{}

if ( ! function_exists('dbQuery')){
  function dbQuery($sql,$debug=0){
	$CI =& get_instance(); 
	$query=$CI->db->query($sql);
	if (!$query){
		logCreate($CI->db->error(),'error');
		logConfig('sql:'.$sql.'|error:'.print_r($CI->db->error(),1),'logDB','error');
		return false;
	}
	else{ 
		if($debug==1){ 	
			logCreate('sql:'.$sql.'|affected:'. $CI->db->affected_rows(),'query');			
		}else{}
		logConfig('sql:'.$sql.'|affected:'. $CI->db->affected_rows(),'logDB','query');
	}	
	
	return $query;
  }
  
}else{}

if ( ! function_exists('dbFetchOne')){
  function  dbFetchOne($sql,$debug=0){
	$query=dbQuery($sql,$debug);
	if(!$query){
		return false;
	}else{
		if($debug==1){
			logCreate('data:'. json_encode($query->row_array()) );
		}else{} 
		return $query->row_array();
	}
  }
  
}else{}

if ( ! function_exists('dbCleanField')){
	function dbCleanField($data0,$erase){
		$data=array();
		if(is_array($data0)){
		foreach($data0 as $name0=>$str){
			$name = str_replace($erase,"", $name0);					
			$intStr=intval($str);
			if((string)$intStr===$str && intval($str)!=0){
				$isInt=true;
			}else{ 
				$isInt=false;
			}
			$data[ $name  ]=$isInt?(int)$str:$str;
		}
		return $data;
		}else{
			return false;
		}
	}
}else{}

if ( ! function_exists('dbFetch')){
  function dbFetch($sql,$type=0,$debug=0){
	$query=dbQuery($sql,$debug);
	if(!$query){
		return false;
	}else{
		
		$data=array();
		if($type==0){
		  foreach ($query->result_array() as $row){
			$data[]=$row;
		  }
		} 
		else{ 
		  foreach ($query->result() as $row){
			$data[]=$row;
		  }
		}
		if($debug==1){
			logCreate('data total:'. $query->num_rows() .'| list:'.json_encode($data));
		}else{} 
		
		return $data;
	}
  }
  
}else{}

if ( ! function_exists('dbInsert')){
	function dbInsert($table, $data){
	$CI =& get_instance();
		$sql=$CI->db->insert_string($table,$data);
	//	logCreate($sql,'query');
		dbQuery($sql);
	}
}

if ( ! function_exists('saveTableLog')){
 function saveTableLog($controller, $func, $param){
	$id=dbId('log',100000);
	$CI =& get_instance();
	$data=array(
		'controllers'=>$controller,
		'function'=>$func,
		'param'=>json_encode($param)
	);
	$sql=$CI->db->insert_string($CI->forex->tableLog, $data);
    //dbQuery($sql);
	return true;
	
 }
}
/*
if ( ! function_exists('dbInsert')){
  function  dbInsert($table,$array=0){
	$sql=$CI->db->insert_string($table, $array);
    dbQuery($sql);

  }
  
}else{}
*/