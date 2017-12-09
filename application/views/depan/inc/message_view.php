<?php
$message = $this->session->flashdata('messages');
?>
<div class="main col-md-12" id='info_message'>
    <div class="panel">
        <div class="panel-heading partition-<?= $message['status'] ? 'blue' : 'red'; ?>">
            <span class="text-bold">Information</span>
        </div>
        <div class="panel-body partition-white">
            <?= $message['message']; ?>
        </div> 
    </div>
</div>