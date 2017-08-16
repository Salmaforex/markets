<?php 
$detail_widthdraw=$this->session->flashdata('info');
if(defined('LOCAL')){
	echo_r($detail_widthdraw);
	
}

if($detail_widthdraw){
?>
<div class="main col-md-12">
        <div class="panel">
          <div class="panel-heading partition-blue">
           <span class="text-bold">Withdrawal Success</span>
          </div>
          <div class="panel-body partition-white">
          	<p>Dear, <?=$detail_widthdraw['name'];?></p>
             
            <div class="well no-padding">
            <table class="table editable no-margin">
                    <tbody>
                      <tr>
                        <th width="200">Date</th>
                        <td><?=date("Y-m-d H:i:s");?></td>
                      </tr>
                      <tr>
                        <th>Withdrawal to Account</th>
                        <td><?=$detail_widthdraw['account'];?></td>
                      </tr>
                      <tr>
                        <th>Bank Name</th>
                        <td><?=$detail_widthdraw['bank'];?></td>
                      </tr>
                      <tr>
                        <th>Bank account number</th>
                        <td><?=$detail_widthdraw['norek'];?></td>
                      </tr>
                      <tr>
                        <th>Bank account name</th>
                        <td><?=$detail_widthdraw['namerek'];?></td>
                      </tr>


                      <tr>
                        <th>Withdrawal</th>
                        <td><?=number_format($detail_widthdraw['orderWidtdrawal'],2);?></td>
                      </tr>
                      <tr>
                        <th>Total</th>
                        <td><?=$detail_widthdraw['symbol'];?> <?=number_format($detail_widthdraw['order1'],2);?></td>
                      </tr>
                    </tbody>
                  </table>
              </div>
			  <div>
			  Please wait 1-3 x 24 hours , your order will be forwarded to the Finance Departement for in the process.salmamarkets finance working hours from Monday - Friday at 09:00 am - 17:00 pm . Hopefully this information is useful .
			  </div>
 
          </div>
        </div>
      </div>
<?php
}
