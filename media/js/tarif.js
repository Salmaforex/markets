jQuery(document).ready(function() {
    jQuery('#tblTarif').DataTable( {
		"columnDefs": [
            {
                 
            }
		],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
		"lengthMenu": [
                [3,5, 10,15, 20, 35],
                [3,5, 10,15, 20, "max 35"] // change per page values here
            ],
        "columns": [
			{ "data": "created" },
			{ "data": "types"},
            { "data": "price1" },
                         
        ]
    } );
} );

function detail(id){
	params={id:id,type:"apiDetail"}
	respon=sendAjax( urlDetail,params);
	respon.success(function(result,status) {
		if(result.status==true){ 
			jQuery(".modal-title").html(result.data.title);
			jQuery(".modal-body").html(result.data.html);
		}else{
			jQuery(".modal-title").html("WARNING");
			jQuery(".modal-body").html(result.message);
			
		}
		
		//jQuery("#myModal").modal({show: true}).css("height","150%");	
		jQuery("#preview").html(result.data.html);
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