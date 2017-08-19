<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$rand_num= 8;//rand(1000, 9000);
?>
<!doctype html>
<html>
    <head>
        
    </head>
    <body>
        <div style="width:90%;margin:30px auto 20px">
        <?php
        $aContent= !is_array($content) ?(array)$content:$content;
	foreach($aContent as $load_view){
            $this->load->view($baseFolder.'contents/'.$load_view."_view");
	}
        
        if(isset($post)){
            echo '<hr/>post:';
            echo_r($post);
        }
        
        if(isset($result)){
            echo '<hr/>result API:';
            echo_r($result);
        }
        
        $this->load->view($baseFolder.'inc/list_view');
        ?> 
        </div>
    </body>
</html>