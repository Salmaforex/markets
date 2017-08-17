<?php 
if(!isset($defInput)){ 
	$defInput="";
}
if(!isset($showForm)){ 
	$showForm=1;
}
/*
$showAgent=true;
if(!isset($showAgent)){ 
	$showAgent=true;
}

            <div class="alert alert-success">
              <button data-dismiss="alert" class="close"> × </button>
              <strong>Well done!</strong> You successfully read <a class="alert-link" href="#"> this important alert message</a> . </div>
*/
//if(isset($register)){ print_r($register); }
?><form novalidate="novalidate" name="frm" id0="frm" id="frmLiveAccount" method="POST"  class="form-horizontal" role="form" style2="" 
      action="<?=site_url('guest/register');?>" >
<?php callback_submit(); ?>
		<input type='hidden' name='type' value='request' />
		<div class="frame-form-basic"> 
			<?=bsInput2( lang('forex_fullname'),'name', isset($register['name'])?$register['name']:'', lang('forex_inputsuggestion') );?> 
<?php 
//=bsInput2( lang('forex_country'),'country', isset($register['country'])?$register['country']:"Indonesia", lang('forex_inputsuggestion'),false, $showForm  );
$dataCountry = $this->country_model->selectValue();
echo bsSelect2( lang('forex_country'),'country', $dataCountry, isset($register['country'])?$register['country']:"Indonesia" );
?>
			<?=bsInput2( lang('forex_city'),'city', isset($register['city'])?$register['city']:$defInput, lang('forex_inputsuggestion2') ,false, $showForm);?>
			<?=bsInput2( lang('forex_state'),'state', isset($register['state'])?$register['state']:$defInput, lang('forex_inputsuggestion2'),false, $showForm );?>
			<?=bsInput2( lang('forex_zipcode'),'zip', isset($register['zip'])?$register['zip']:$defInput, lang('forex_inputsuggestion'),false, $showForm );?>

			<?=bsInput2( lang('forex_address'), 'address',isset($register['address'])?$register['address']:$defInput, lang('forex_inputsuggestion2'),false, $showForm );?> 

			<!--div class='form-group'
<?php 	if($showForm==false){?> style='display:none' <?php } ?>
			>
				 <label for="input_date" class="col-sm-2 control-label"><?=lang('forex_country');?></label>  
				<div class="col-sm-10">
<?php
/*
	$all= $this->country->getAll(); //id only
	$data=array();
	foreach($all as $row){
		$row2=$this->country->getData($row['id']);	
		$data[$row['id']]=$row2['name'];
	}
	$params='class="form-control" id="citizen"';
	echo form_dropdown("citizen",$data, isset($register['citizen'])?$register['citizen']:101,$params);
*/
?>
				</div>
			</div>
		</div-->
<?php
if(isset($showAgent)){
	$agent = isset($agent_code)?$agent_code:false;
//	var_dump($showAgent);
?>
		<?=bsInput2( lang('forex_agent'),'agent', isset($register['agent'])?$register['agent']:$agent, lang('forex_inputsuggestion'),false, $showAgent  );?>
 <?php
}
?>

		<h2>Contact Information</h2>
 
			<?=bsInput2( lang('forex_email'),'email', isset($register['email'])?$register['email']:'', lang('forex_inputsuggestion') );?>
		<?php
                //bsInput2( lang('forex_phone'),'phone', isset($register['phone'])?$register['phone']:$defInput, lang('forex_inputphone'),false,$showForm,true );
                ?>
                <div class="form-group" style="">
                    <label for="input_phone" class="col-sm-2 control-label">Phone</label>
                    <div class="col-sm-10">
                     <input name="phone" value="<?=isset($register['phone'])?$register['phone']:'';?>" id="input_phone" class="form-control" 
                            placeholder="Input your country number +62"  required type="text">

                    </div>
                </div>
                    <?php
/* 		
		<div class='form-group' <?php 	if($showForm==false){?> style='display:none' <?php } ?> >
			<label for="input_date" class="col-sm-2 control-label">Date&nbsp;of&nbsp;Birth</label> 
			<div class="col-sm-10">
			  <input name="dob1" value="<?=isset($register['dob1'])?$register['dob1']:date("d",strtotime("-20 years"));?>" id="input_date" class="dob"  type="text" size=2 /> -
			  <input name="dob2" value="<?=isset($register['dob2'])?$register['dob2']:date("m",strtotime("-20 years"));?>" id="input_date2" class="dob"  type="text" size=2 /> -
			  <input name="dob3" value="<?=isset($register['dob3'])?$register['dob3']:date("Y",strtotime("-20 years"));?>" id="input_date3" class="dob"  type="text" size=2 />
			</div> 
		</div>
*/
?>		
              <div class="form-group">
                <label for="birth" class="col-sm-2 control-label">Date of Birth.</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control"
				  name="dob1" value="<?=isset($register['dob1'])?$register['dob1']:date("d",strtotime("-20 years"));?>" id="input_date" />
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control"
				  name="dob2" value="<?=isset($register['dob2'])?$register['dob2']:date("m",strtotime("-20 years"));?>" id="input_date2" />
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control"
                         name="dob3" value="<?=isset($register['dob3'])?$register['dob3']:date("Y",strtotime("-20 years"));?>" id="input_date3" />
                </div>
              </div>

		<input type='hidden' name='statusMember' value='<?=isset($register['statusMember'])?$register['statusMember']:strtoupper($statAccount);?>' />
		
			<div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-5">
                    <input name="accept" id="acc" value="OK" class="btn btn-info" type="checkbox" />
					<a target='_blank' href='https://www.salmamarkets.com/client-agreement/'>I accept Costumer Agrement</a>
                </div>
            </div> 
			<div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-5">
                    <input name="submit" id="submit" value="Create Account" class="btn btn-info" type="submit" />
                </div>
            </div>
		</div>
        </form>