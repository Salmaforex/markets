<?php 
$detail_deposit=$this->session->flashdata('info');
if($detail_deposit){
?>
<div class="main col-md-12">
        <div class="panel">
          <div class="panel-heading partition-blue">
           <span class="text-bold">Deposit Success</span>
          </div>
          <div class="panel-body partition-white">
          	<p>Dear, <?=$detail_deposit['name'];?></p>
             
            <div class="well no-padding">
            <table class="table editable no-margin">
                    <tbody>
                      <tr>
                        <th width="200">Date</th>
                        <td><?=date("Y-m-d H:i:s");?></td>
                      </tr>
                      <tr>
                        <th>Deposit to Account</th>
                        <td><?=$detail_deposit['account'];?></td>
                      </tr>
                      <tr>
                        <th>Bank Name</th>
                        <td><?=$detail_deposit['bank'];?></td>
                      </tr>
                      <tr>
                        <th>Bank account number</th>
                        <td><?=$detail_deposit['norek'];?></td>
                      </tr>
                      <tr>
                        <th>Bank account name</th>
                        <td><?=$detail_deposit['namerek'];?></td>
                      </tr>


                      <tr>
                        <th>Order</th>
                        <td><?=number_format($detail_deposit['orderDeposit'],2);?></td>
                      </tr>
                      <tr>
                        <th>Total</th>
                        <td><?=number_format($detail_deposit['order1'],2);?></td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <p>
			  Please transfer in accordance with the amount of transfer listed above , the maximum transfer time 1x24 hours . If the transfer is not in that time period , then the system will automatically cancel the order. Hopefully this information is useful .<br/>
			                  Our Bank information :</p>
            <h3>
<?php
//echo_r($detail_deposit);//
		$bank = $key=$this->config->item('forex_bank')[$detail_deposit['currency']];
		foreach($bank as $row){
			echo "\n\t{$row['name']} : <strong>{$row['number']}</strong>  {$row['person']}<br />";
		}
?>  
			</h3>
 
          </div>
        </div>
      </div>
<?php
}
