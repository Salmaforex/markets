$(document).ready(function(e) {
	$('#submit_login').click(function(){
		console.log('submit now');
		//$('#online-login').submit();
		document.getElementById("online-login").submit();
	});
    $('#online-login0').formValidation({
        framework: 'bootstrap',
        err: {
            container: 'tooltip'
        },
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: ' Your Username.'
                    }
                }
            },
			password: {
                validators: {
                    notEmpty: {
                        message: 'Your Password.'
                    }
                }
            },
			captcha: {
                validators: {
                    notEmpty: {
                        message: 'Type Security Code.'
                    }
                }
            },
        }
    });
	$('.drop-nav').find('.toggle').click(function(){
		var th = $(this);
		var target = th.parent().find('ul');
		if(target.css('display') === 'none'){
			target.slideDown();
			th.addClass('glyphicon-chevron-up');
			th.removeClass('glyphicon-chevron-down');
		}else{
			target.slideUp();
			th.removeClass('glyphicon-chevron-up');
			th.addClass('glyphicon-chevron-down');
		}
	});
try{
	$('#history-table').dataTable( {
		"ajax": urlAPI,
		"lengthMenu": [
                [5, 10,15, 20, 35],
                [5, 10,15, 20, "max 35"] // change per page values here
            ],
	} );
//	console.log('history');
}
catch(err){
//	console.log(err);
//	console.log('not history');
}
 

});
//==============OLD===

try{
	target0=jQuery("#input_orderDeposit");
//	target0.val(10);
	orderDeposit();
	jQuery("#input_orderDeposit,#input_order1,#input_currency").keyup(function(){
		orderDeposit();
	});
	jQuery("#input_currency").change(function(){
		orderDeposit();
	});
	jQuery("#input_orderDeposit,#input_order1").blur(function(){
		orderDeposit();
	});
}
catch(err){
//	console.log(err);
//	console.log('not Deposit');
}

try{
	target0=jQuery("#input_orderWidtdrawal");
	target0.val(10);
	orderWidtdrawal();
	jQuery("#input_orderWidtdrawal,#input_order1,#input_currency").keyup(function(){
		orderWidtdrawal();
	});
	jQuery("#input_orderWidtdrawal,#input_order1").blur(function(){
		orderWidtdrawal();
	});
        jQuery("#input_currency").change(function(){
		orderWidtdrawal();
	});
}
catch(err){
//	console.log(err);
//	console.log('not Widtdrawal');
}

function clearModal(){
	jQuery(".modal-title, .modal-body").empty();
}
 
function orderWidtdrawal0(){
	target0=jQuery("#input_orderWidtdrawal");
	dolar=0;
	jQuery.post(urlWidtdrawal,function(dolar){
		target=jQuery("#input_order1");
		input0=isNaN(parseFloat(target0.val()))?10:target0.val();
//		console.log(input0);
		target.val( input0 * dolar);
		target=jQuery("#input_rate");
		target.html(  "Rp "+ dolar);
	});
}

function orderWidtdrawal(){
	target0=jQuery("#input_orderWidtdrawal");
	target1=jQuery("#input_currency");
	params={currency:target1.val(),type:"deposit"}
	console.log(params);
	//respon=sendAjax( urlDeposit,params);
	var respon = jQuery.ajax({
          url: urlWidtdrawal,
          type: "POST",
          data: params,
          dataType: "json", 
          cache:false,
          timeout:20000, 
        });
	
//	console.log(respon);
	respon.success(function(result, status) {
		console.log(result);
		if(result.status==true){ 
			dolar = result.value;
			show_txt = result.show_txt;
			target=jQuery("#input_order1");
			input0=isNaN(parseFloat(target0.val()))?10:target0.val();
			total0=Math.round(input0 * dolar * 1000);
			total=total0/1000;
//			console.log(input0,dolar,total0,total);
			target.val( total );
			target=jQuery("#input_rate");
			target.html(show_txt);
		}
		else{
			console.log('error');
			//jQuery(".modal-title").html("WARNING");
			alert(result.message);
		}	
	});
}

function orderDeposit(){
	target0=jQuery("#input_orderDeposit");
	target1=jQuery("#input_currency");
	params={currency:target1.val(),type:"deposit"}
	//console.log(params);
	//respon=sendAjax( urlDeposit,params);
	var respon = jQuery.ajax({
          url: urlDeposit,
          type: "POST",
          data: params,
          dataType: "json", 
		  cache:false,
		  timeout:20000, 
    });
	
	//console.log(respon);
	respon.success(function(result, status) {
		//console.log(result);
		if(result.status==true){ 
			dolar = result.value;
			show_txt = result.show_txt;
			target=jQuery("#input_order1");
			input0=isNaN(parseFloat(target0.val()))?10:target0.val();
			total0=Math.round(input0 * dolar * 1000);
			total=total0/1000;
			//console.log(input0,dolar,total0,total);
			target.val( total );
			target=jQuery("#input_rate");
			target.html(show_txt);
		}
		else{
			console.log('error');
			//jQuery(".modal-title").html("WARNING");
			alert(result.message);
		}	
	});
}

function orderDeposit0(){
//	console.log(target0.val());
	dolar=0;
	jQuery.post(urlDeposit,function(dolar){
	//	console.log(dolar );
		target=jQuery("#input_order1");
		input0=isNaN(parseFloat(target0.val()))?10:target0.val();
	//	console.log(input0);
		target.val( input0 * dolar);
		target=jQuery("#input_rate");
		target.html(  "Rp "+ dolar);
	});
}

/* FOREX */
function createLiveUser(){
	var url=siteUrl+"forex/data";
	var formData=jQuery("#frmLiveAccount").serializeArray();
	
	params={type:"request",data:formData}
	stat=checkInput();
    clearModal();
	if(stat==0){
		//alert('please check your input');
            jQuery("#myModal").modal({show: true}).css("height","150%");
            jQuery(".modal-title").html("WARNING");
            jQuery(".modal-body").html("please check your input");
            return false;
	}
	
	respon=sendAjax(url,params);
	respon.success(function(result,status) {
            if(result.status==true){ 
                    jQuery(".modal-title").html(result.data.title);
                    jQuery(".modal-body").html(result.data.html);
//			window.open(url,'_blank');
            }else{
                    jQuery(".modal-title").html("WARNING");
                    jQuery(".modal-body").html(result.message);

            }

            jQuery("#myModal").modal({show: true}).css("height","150%");
            //console.log("success");	
            //console.log(result);			
       });
       respon.error(function(xhr,status,msg){			
			//console.log("Error");
			//console.log(status);
			//console.log(msg);
			//console.log(xhr);
			
        });
}
function checkEmail(target){
	return true;
   // var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
   // return re.test(target.val());
	
}
function checkInput(){
	stat=1
	//stat=checkMoreThan(jQuery('#input_name'),2);
	if(stat==1)stat=checkMoreThan(jQuery('#input_address'),2);
	if(stat==1)stat=checkMoreThan(jQuery('#input_state'),2);
	if(stat==1)stat=checkMoreThan(jQuery('#input_city'),2);
	if(stat==1)stat=checkMoreThan(jQuery('#input_zipcode'),2);
	//if(stat==1)stat=checkMoreThan(jQuery('#input_agent'),2);
	if(stat==1){
		stat=checkEmail(jQuery('#input_email'),2);
		if(stat==false){
			jQuery('#input_email').css('border-color','#ff2323') ;
			jQuery('#input_email').css('border-width','3px') ;
		}
	}
	if(stat==1||stat==true){		
		jQuery('#input_email').css('border-color','#e1e1e1') ;
		jQuery('#input_email').css('border-width','1px') ;
		stat=checkMoreThan(jQuery('#input_phone'),2);
	}else{ 
		//console.log('error email :');
		//console.log(stat);
		
	}
	return stat;
}
