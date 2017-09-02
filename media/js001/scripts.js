$(document).ready(function(e) {
    $('#online-login').formValidation({
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
	$('.drop-nav').find('.toggle').click(function(e){
		var th = $(this);
		var target = th.parent().find('ul');
		if(target.css('display') === 'none'){
			target.slideDown('fast');
			th.addClass('fa-chevron-up');
			th.removeClass('fa-chevron-down');
		}else{
			target.slideUp('fast');
			th.removeClass('fa-chevron-up');
			th.addClass('fa-chevron-down');
		}
		e.preventDefault();
	});
	$('.fancy-drop-nav').find('.parent').click(function(e){
		var th = $(this);
		var target = th.parent().find('ul');
		if(target.css('display') === 'none'){
			th.parent().addClass('open');
			$('.drop-nav li').not(th.parent()).removeClass('open');
			target.slideDown();
			$('.fancy-drop-nav ul li ul').not(target).slideUp();
		}else{
			target.slideUp();
		}
		e.preventDefault();
	});
});