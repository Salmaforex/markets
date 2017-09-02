<?php
//echo_r($userlogin);die();
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';
//$name=isset($userlogin['firstname'])?$userlogin['firstname']:'';
//." ".$userlogin['lastname'];
//$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
$accountid=isset($accounts[0]['accountid'])?$accounts[0]['accountid']:'-';

?>

  <div class="container">
    <div class="row">
	<?php
	?>
	<!--2-->
      <div class="main col-md-9">
        <div class="row">

        </div>
        <div class="panel panel-white">
          <div class="panel-heading border-light">
            <h3><strong>Detail User</strong></h3>
		</div>
        </div>
        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> Summary</span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
<?php 
if(isset($html) ){
	echo $html;
}
else{ 
	echo 'nothing to see';
}
?>	
		  </div>
          </div>
        </div>
      </div>
    </div>
  </div>
