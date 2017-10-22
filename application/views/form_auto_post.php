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
        <?= form_open($target, '', $params); 
        if(isset($fp_cart)){
            foreach($fp_cart as $key=>$values){
                foreach($values as $name=>$val){
                ?><input type="hidden" name="fp_cart[<?=$key;?>][<?=$name;?>]" value="<?=$val;?>" />
                    <?php
                }
            }
        }

?>
           
                
<input name="" type="submit">
</form>
        </div>
    </body>
</html>