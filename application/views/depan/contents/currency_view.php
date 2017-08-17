<?php
$name=isset($userlogin['name'])&&$userlogin['name']!=''?$userlogin['name']:'User Forex';//$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname'];
//=========currency
$currency=array();
$currency_list = $this->forex->currency_list();
foreach($currency_list as $row){
    $currency[$row['code']]=$row['name'].' ( '.$row['symbol'].' )';

}
?>

<div class="container">
    <div class="row">
	<?php
	$this->load->view('depan/inc/left_view');
	?>
      <div class="main col-md-9">
	<?php
	//$this->load->view('depan/inc/partner_top_view');
	?>

        <div class="panel panel-white">
          <div class="panel-heading partition-blue"> <span class="text-bold"> 
		  UPDATE CURRENCY
		  </span> </div>
          <div class="panel-body no-padding">
            <div class="row no-margin">
		  <!--start here-->
<?php
//	$rand_url=url_title("{$userlogin['accountid']}-{$userlogin['firstname']}","-");
//	$urlAffiliation=site_url("register/{$rand_url}");
?>		
		<div class="main ">
			<form   name="frm"  id="frmTarif" method="POST"   role="form">
		<div class="frame-form-basic">
		<h2>Ganti Harga / Rate</h2>
		  <div class="panel-body" >
			<table class='table-striped table' border="0">
<?=bsInput('Code ','code', '','IDR,MYR etc' );?>
<?=bsInput('Name ','name', '','Rupiah' );?>
<?=bsInput('Symbol ','symbol', '0','simbol mata uang' );?>
<?=bsInput('Detail ','detail', '','??' );?>

<tr><td colspan=3> 
            <button type="submit" class="btn btn-default submitLogin" >
              Submit
            </button></td></tr>
			</table>

		</div></div>
	</form>
	<div style='padding:20px;overflow:auto'>
	<table id="tblCurrency" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					 <th>Created</th> 
					<th>Currency</th>
					<th>Detail</th>
				   
				</tr>
			</thead>
                        <tbody>
<?php
        foreach($currency_list as $row){
            $s='<tr>';
            $s.='<td>'.$row['modified'].'</td>';
            $s.="<td>{$row['code']}<br/>{$row['symbol']}</td>";
            $s.="<td>{$row['name']}<hr/> {$row['detail']}</td>";
            $s.='</tr>';
            echo $s;
        }

?>
                        </tbody>
			<tfooter>
				<tr>
					 <th>Created</th> 
					<th>Currency</th>
					<th>Detail</th>
				   
				</tr>
			</tfooter>
	</table>
	</div>
<?php 
echo_r($currency_list);
?>
<script>
urlAPI="<?=site_url("member/data?type=tarif");?>";
urlDetail="<?=site_url("member/data");?>";
</script>	
		</div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>