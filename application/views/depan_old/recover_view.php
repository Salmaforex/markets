<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! function_exists('logFile')){ logFile('view/member','recover_view.php','view'); };
?>
<div class='container'>
    <div style='margin-top:30px;'>
        <form novalidate="novalidate" name="frm" id0="frm" id="frmLiveAccount" method="POST"  class="form-horizontal" role="form"> 
			<div class="frame-form-basic">
			<h2>Recover Your User</h2>
      
          <div class="panel-body">
<?php 
if(isset($raw['code'])&&$raw['code']==266){
	echo '<h4>'.$raw['message'].'</h4>';
}
else{ 
	echo 'Your link has expired.  <a href="'.base_url('member/forgot')
	.'">Click Here to try Again.</a> ';
}
?>	
		  </div>
          <div class='clear'></div> 
        </div>
      </form>
    </div>
     
</div>
<script>

</script>