<?php

function run_api($url, $parameter=array()){
	global $maxTime;
	if( $maxTime==null ) $maxTime=10;
	if(isset($parameter['maxTime'])) $maxTime=$parameter['maxTime'];
	log_info_table('run_api',array('old',$url,count($parameter) )); 
	

	$dtAPI=array('url'=>$url);
	if(count($parameter)){
		$logTxt="func:_runApi| url:{$url}| param:".http_build_query($parameter,'','&'); 
	}
	else{ 
		$logTxt="func:_runApi| url:{$url}"; 
		$parameter['info']='no post';		
	}
	//$parameter[]=array('server'=>$_SERVER);
	$dtAPI['parameter']=json_encode($parameter);
	logCreate( 'API: '.$logTxt); 
		
	if(count($parameter)){	 	
		logCreate( 'API: '."url:{$url}| param:\n".print_r($parameter,1),'debug');
	}else{ 
		logCreate( 'API: param:'.print_r(parse_url($url),1),'debug');
	}
		$curl = curl_init();
		 
		curl_setopt($curl, CURLOPT_URL, $url  );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if($parameter != '' && count($parameter)!=0 ) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, $maxTime);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameter,'','&'));
			//curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@/path/to/file.ext');
			//if( isset($_SERVER['HTTP_USER_AGENT']) )
			//	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
			logCreate('API:POST','info');
		}
		else{ 
			logCreate('API:GET','info');
		}
		curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$response = curl_exec($curl);

		if (0 != curl_errno($curl)) {
			$response = new stdclass();
			$response->code = '500';
			$response->message = curl_error($curl);
			$response->maxTime = $maxTime;
			$dtAPI['response']=json_encode($response );
			$dtAPI['error']=1;
		}
		else{
			$response0 = $response; 
			$dtAPI['response']= $response ;
			$dtAPI['error']=0;
			$response = json_decode($response,1);
			if(!is_array($response)){
				$response=$response0;
				$dtAPI['error']=1;
			}
			else{
				$dtAPI['error']=0;
			}
		}
		
		curl_close($curl);
		if(!isset($response0)) $response0='?';
		logCreate( 'helper| API| url:'. $url. "|raw:".(is_array($response)?'total array/obj='.count($response):$response0 ) );
		
	    //$CI->db->insert($CI->forex->tableAPI,$dtAPI);
		
		return $response;
			
	}