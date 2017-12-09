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
        <?= form_open($target, 'name="form_auto_submit"', $params); 
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
        <script type="text/javascript">

    window.onload=function(){
        document.forms["form_auto_submit"].hidden = true;
        document.forms["form_auto_submit"].submit();
        var auto = setTimeout(function(){ autoRefresh(); }, 500);


        function submitform(){
         // alert('test');
          document.forms["form_auto_submit"].submit();
          return true;
        }

        function autoRefresh(){
           clearTimeout(auto);
           auto = setTimeout(function(){ submitform(); autoRefresh(); }, 900);
           return true;
        }
    }

</script>
    </body>
</html>