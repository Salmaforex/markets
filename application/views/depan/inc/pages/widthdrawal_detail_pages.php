<?php
$row = $this->forex->flow_data($flow_id);
$detail=isset($row['detail'])?$row['detail']:false;
$rate = $this->forex->currency_by_code($row['currency']);

$driver_core = 'advforex';
$func_name='execute';
$driver_name='forex_balance';
$result =  $this->{$driver_core}->{$driver_name}->{$func_name}(array($detail['account']));
//echo_r($result);
$balance = isset($result['margin']['Balance'])?$result['margin']['Balance']:0;
?>
<table width="650" border="0" align="center" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td><table width="100%" cellpadding="2">
        <tbody>
          <tr>
            <td width="60%" height="94"><a href="https://www.salmamarkets.com/"><img src="https://www.salmamarkets.com/wp-content/uploads/2017/02/salmamarket240.png" width="254" height="67" /></a></td>
            <td width="40%" align="center"><?=date("Y-m-d H:i:s");?></td>
          </tr>
        </tbody>
      </table>
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Widthdrawal Order Detail<br />
         
      </p>

        <table width="641" border="0">
          <tr>
            <td width="572" height="33" bgcolor="#0099cc"> Here your order Widthdrawal detail:.</td>
          </tr>
        </table>
        <table border="0" align="center" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3312">
          <tbody id="m_7063596389365770691yui_3_16_0_1_1450323941636_3311">
            <tr id="m_7063596389365770691yui_3_16_0_1_1450323941636_3324">
              <td width="276" bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3323"><strong>Date</strong></td>
              <td width="419" bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3337"><strong>: </strong><?=date("Y-m-d H:i:s", strtotime($row['created']) );?></td>
            </tr>
            <tr id="m_7063596389365770691yui_3_16_0_1_1450323941636_3322">
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3321"><strong>Widthdrawal to account</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3338"><strong>: </strong><?=$detail['account'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_"><strong>Name</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_2"><strong>: </strong><?=$detail['name'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_3"><strong>Bank Name</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_4"><strong>: </strong>
			  <?=$detail['bank'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_5"><strong>Account Bank Number</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_6"><strong>: </strong><?=$detail['norek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_7"><strong>Account Bank Holder</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_8"><strong>: </strong><?=$detail['namerek'];?></td>
            </tr>
            <tr>
              <td bgcolor="#e9e9e9" id="m_7063596389365770691yui_3_16_0_1_1450323941636_9"><strong>Balance Amount ( USD )</strong></td>
              <td bgcolor="#e9e9e9" id="m_7063596389365770691yui_3_16_0_1_1450323941636_10"><strong>: </strong>$ <?=number_format($balance,3);?></td>
            </tr>
            <tr>
              <td bgcolor="#d5d9e0" id="m_7063596389365770691yui_3_16_0_1_1450323941636_9"><strong>Widthdrawal Amount ( USD )</strong></td>
              <td bgcolor="#d5d9e0" id="m_7063596389365770691yui_3_16_0_1_1450323941636_10"><strong>: </strong>$ <?=number_format($detail['orderWidtdrawal'],3);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_11"><strong>Widthdrawal Amount ( <?=$rate['code'];?> )</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_12"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($detail['order1'],2);?></td>
            </tr>
            <tr>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_13"><strong>Rate ( <?=$rate['code'];?> )</strong></td>
              <td bgcolor="#CCCCCC" id="m_7063596389365770691yui_3_16_0_1_1450323941636_14"><strong>: </strong><?=$rate['symbol'];?> <?=number_format($detail['rate'] ,2);?></td>
            </tr>
          </tbody>
        </table>

      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td><p>Our Bank information :</p>
        <h3>
<?php
		$bank = $key=$this->config->item('forex_bank');
		foreach($bank[$rate['code']] as $row){
			echo "\n\t{$row['name']} : <strong>{$row['number']}</strong>  {$row['person']}<br />";
		}
?>  
      <hr align="center" noshade="noshade" /></td>
    </tr>
    <tr>
      <td>
	  <a href='<?=site_url('admin/widthdrawal/approve/'.$flow_id);?>'><button>Approve</button></a>
	  <a href='<?=site_url('admin/widthdrawal/cancel/'.$flow_id);?>'><button>Cancel</button></a>
	  </td>
    </tr>
  </tbody>
</table>
