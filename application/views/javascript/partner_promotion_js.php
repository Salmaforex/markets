/*
partner_promotion
*/
$(document).ready(function(){
	$.event.special.copy.options.trustedDomains = ["*"];
	var afiliateUrl = 'http://google.com'
	var userIdent = 'userId'
    $('#sampletable').DataTable({
			select: {
				className: 'form-control'
			}
		});
	$('.options button').click(function(){
		var th = $(this)
		var parent = th.parent().parent().parent().parent()
		var data = th.data()
		parent.find('.preview img').attr('src', data.image)
		parent.find('.desc').html(data.text)
		th.addClass('active')
		$('.options button').not(th).removeClass('active')
	})
	$('.getcode').click(function(e){
		e.preventDefault()
		var selectedItem = $('.options').find('.active');
//		console.log(selectedItem.data('dimension') );
		var data={gambar:selectedItem.data('image'),url:"<?=base_url('register/'.$get['account_id'].'_'.url_title($get['name']));?>"}
		obj=ajaxPostJson('<?=site_url('partner/promotion_html');?>', data,false);
		console.log(obj);
		str_ajax=obj.result.html;
		$('#codeModal').find('.theCode').text(str_ajax);
/*'<a href="<?=base_url('register/'.$get['account_id'].'_'.url_title($get['name']));?>"><img src="'+selectedItem.data('image')+'" /></a>'*/
		$('#codeModal').modal('show');
	})
	$("body").on("copy", ".zclip", function(/* ClipboardEvent */ e) {
		console.log(e)
		e.clipboardData.clearData();
		e.clipboardData.setData("text/plain", $('#codeModal').find('.theCode').text());
		e.preventDefault();
	});
});