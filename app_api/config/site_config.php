<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$config['pages']=array(
        array('demo/basic','basic driver','memakai driver secara standar. Saya akan selalu mengembalikan nilainya berupa parameter'),
        array('demo/basic/debug','basic driver (debug)','memakai driver secara standar. Kembalian yang menampilkan debug. Sangat tidak disarankan!'),
        array('demo/action','driver dengan konsep 1 call','memakai driver yang menyesuaikan pembahasan di grup PHP. '
            . 'Metode ini mirip seperti yang dibahas, tetapi masih jauh dari OK. Driver tetap diberikan parameter (array) namun dipisah array untuk '
            . 'data dan perintahnya. '),
        array('demo/action/debug','driver dengan konsep 1 call (debug)','memakai driver yang menyesuaikan pembahasan di grup PHP. '
            . 'Metode ini mirip seperti yang dibahas, tetapi masih jauh dari OK. Driver tetap diberikan parameter (array) namun dipisah array untuk '
            . 'data dan perintahnya. kembalian dalam bentuk Debug'),
    
    );
//=============khusus Driver===========
$config['drivers_komunitas']=array(
    'demo'
);