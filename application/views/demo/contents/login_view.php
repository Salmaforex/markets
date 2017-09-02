<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo form_open_multipart();
$data = array(
        'name'          => 'username',
        'id'            => 'username',
       'autocomplete'         => 'off',
        'maxlength'     => '100',
        'size'          => '50',
        'style'         => 'width:50%'
);

echo "Username:<br/>".form_input($data);
$data = array(
        'name'          => 'password',
        'id'            => 'password',
        'type'      =>'password',
//        'value'         => 'johndoe',
        'maxlength'     => '100',
        'size'          => '50',
        'style'         => 'width:50%'
);

echo "<p>Password:<br/>".form_input($data);
echo bsToken();
echo "<hr/>".form_submit('mysubmit', 'Submit Post!');
echo '</form>';