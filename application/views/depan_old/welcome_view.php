<?php
$name=$userlogin['detail']['firstname']." ".$userlogin['detail']['lastname']; 
?>
	<div class="container">
    	<div class="row">
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/leftmenu_view':'leftmenu_view';
	$this->load->view($load_view);
?>		
			<div class="main col-md-8">
            	<h3 class="orange nomargin"><strong>Welcome to the Secure Area of SalmaForex</strong></h3>
                <p>Dear <?=isset($userlogin['detail']['firstname'])?$userlogin['detail']['firstname']:'';?>&nbsp;<?=isset($userlogin['detail']['lastname'])?$userlogin['detail']['lastname']:'';?>,<br/>
				Your are now logged-in the Secure Area. Here you can view all the Information from your accounts. You can also Update Your Profile before deposit and withdrawn and many more. </p>
		<?php
		$showAgentMenu0=isset($userlogin['type'])&&$userlogin['type']=='agent'?true:false;
		$showAgentMenu1=isset($userlogin['patner'])&&$userlogin['patner']!=0?true:false;
		$showAgentMenu=$showAgentMenu0||$showAgentMenu1?true:false;
		if($showAgentMenu){
		?>
			<div class="vspace-30"></div>
                <div class="box yellow-box">
					<div class="box-padder30">
						<table width="100%">
                            <tr>
								<td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>List Patner</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='<?=base_url('member/listApi/partner');?>'><strong>
											<?=$userlogin['patner'];?> Partner(s)
											</strong></a>
                                        </div>
                                    </div>
                                    <div class="vspace-30"></div>
                                </td>
                                <td>
                                    <div class="vmargin-15"><span class="xlarge glyphicon glyphicon-arrow-right"></span></div>
                                    <div class="vspace-30"></div>
                                </td>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Patner Revenue</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='#' href1='<?=base_url('member/listApi/patner_revenue');?>'><strong>
											<?=number_format($userlogin['balance'],2);?>
											</strong></a>
                                        </div>
                                    </div>
                                    <div class="vspace-30"></div>
                                </td>
                            </tr>
						</table>
						
						<h3>Link Affiliation:</h3>
						<div>
						<font size='+2'><?php 
						$rand_url=url_title("{$detail['accountid']}-{$detail['detail']['firstname']}","-");
						$urlAffiliation=base_url("register/{$rand_url}");
							echo anchor_popup($urlAffiliation,$urlAffiliation);?></font>
						</div>
					</div>
				</div>
		<?php
		}
		?>
                <div class="vspace-30"></div>
                <div class="box yellow-box">
                	<div class="box-padder30">
                        <table width="100%">
                            <tr>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Verify Account</strong></p>
                                            <div class="vspace-15"></div>
					<?php
							if(isset($userlogin['document']) &&isset($userlogin['document']['status']) && $userlogin['document']['status']==1){
								?><a class="btn btn-default" href='<?=base_url('member/edit');?>'><strong>Verified</strong></a><?php 
							}
							else{?>
                                            <a class="btn btn-default" href='<?=base_url('member/uploads');?>'><strong>Click Here</strong></a>
					<?php
							}?>
                                        </div>
                                    </div>
                                    <div class="vspace-30"></div>
                                </td>
                                <td>
                                    <div class="vmargin-15"><span class="xlarge glyphicon glyphicon-arrow-right"></span></div>
                                    <div class="vspace-30"></div>
                                </td>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Change Password</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='<?=base_url('member/editpassword');?>'><strong>Click Here</strong></a>
                                        </div>
                                    </div>
                                    <div class="vspace-30"></div>
                                </td>
                                <td>
                                    <div class="vmargin-15"><span class="xlarge glyphicon glyphicon-arrow-right"></span></div>
                                    <div class="vspace-30"></div>
                                </td>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Update Detail</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='<?=base_url('member/edit');?>'><strong>Click Here</strong></a>
                                        </div>
                                    </div>
                                    <div class="vspace-30"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Create Deposit</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='<?=base_url('deposit-form');?>'><strong>Click Here</strong></a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="vmargin-15"><span class="xlarge glyphicon glyphicon-arrow-right"></span></div>
                                </td>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Create Withdraw</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='<?=base_url('withdraw-form');?>'><strong>Click Here</strong></a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="vmargin-15"><span class="xlarge glyphicon glyphicon-arrow-right"></span></div>
                                </td>
                                <td>
                                    <div class="box solid blue-box text-center">
                                        <div class="box-padder15">
                                            <p class="bright large"><strong>Get Help</strong></p>
                                            <div class="vspace-15"></div>
                                            <a class="btn btn-default" href='http://salmaforex.com'><strong>Click Here</strong></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
						<?php if(defined('LOCAL')) echo '<pre>'.print_r($userlogin,1).'</pre>';?>
                    </div>
                </div>
            </div>
    	</div>
    </div>