<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<ol>
    <?php
    foreach($list_option as $key=>$title){
        echo "<li>".anchor(site_url("demo/{$key}"),$title)."</li>\n";
    }
    ?>
</ol>