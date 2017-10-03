jQuery(document).ready(function() {
try{	
    jQuery('#tableAPI').DataTable( {
		"columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) { 
                    return '<input type="button"  value="detail" onclick="detail('+ row.id+')" />';
					
                },
                "targets": 3
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
        "lengthMenu": [
                [5, 10,15, 20, 35],
                [5, 10,15, 20, "max 35"] // change per page values here
            ],
        "columns": [			
            { "data": "created"},
            { "data": "url" },
            { "data": "param" }
        ]
    } );
}
catch(err){
	//console.log('not tableAPI');
}

try{	
	tableDeposit=jQuery('#tableDeposit').DataTable( {
		"columnDefs": [
            {}
		],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
		"order": [[ 0, "desc" ]],
		"lengthMenu": [
                [3,5, 10,15, 20, 35,50,100],
                [3,5, 10,15, 20, "max 35",50,100] // change per page values here
            ],
        "columns": [
			{ "data": "created"},
		//	{ "data": "flowid"},
			{ "data": "accountid"},
            { "data": "raw.username","orderable": false },
            { "data": "raw.name","orderable": false },
            { "data": "raw.orderDeposit","orderable": false },
			{ "data": "detail","orderable": false },
            { "data": "status","orderable": false },
            { "data": "action","orderable": false },             
        ]
    } );
	//console.log('table deposit ready');
}
catch(err){
//	 console.log('not table Deposit');
//	 console.log(err);
}	

try{	
	tableWidtdrawal=jQuery('#tableWidtdrawal').DataTable( {
		"columnDefs": [
            { 
			
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"lengthMenu": [
                [3,5, 10,35,50,100],
                [3,5, 10,35,50,100] // change per page values here
            ],
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
        "columns": [
			{ "data": "created"},
			{ "data": "accountid"},
            { "data": "dt.username" },
            { "data": "dt.name" },
            { "data": "dt.orderWidtdrawal" },
            { "data": "detail" },
            { "data": "status" },
            { "data": "action" },             
        ]
    } );
	//console.log('table widtdrawal ready');
}
catch(err){
//	 console.log('not table Widtdrawal');
//	 console.log(err);
}

try{
	console.log('table user');
	tableUsers=jQuery('#tableUsers').DataTable( {
		"columnDefs": [
            { 
				"render": function ( data, type, row ) { 
				console.log(row);
				msg = '<input type="button"  value="detail" onclick="detailUser('+ row.id+')" /><input type="button"  value="Edit" onclick="editUser(\''+ row.id+'\')" />';
				if(row.users.type_user !='patners'){
					msg = msg+'<input type="button"  value="sebagai Agent" onclick="changeAgent('+ row.id+')" /> ';//+row.users.type_user ;
				}
                    return msg;
					
                },
                "targets": 6
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
		"order": [[ 0, "desc" ]],
        "columns": [
			{ "data": "created"},
			{ "data": "name","orderable": false},
            { "data": "usertotal","orderable": false },
            { "data": "email" },
            { "data": "accounttype","orderable": false },
            { "data": "status", "orderable": true },     
            { "data": "action","orderable": false },             
        ],
		"lengthMenu": [
                [5, 10,15, 20, 35],
                [5, 10,15, 20, "max 35"] // change per page values here
            ],
    } );
	//console.log('table widtdrawal ready');
	function activeStatus(id){
		console.log('kirim ajax dgn id');
		params={id:id,type:"activeUserStatus"}
		url=urlChangeStatus;
		req=sendAjax(url,param);
		console.log(req);
	}
	function reviewStatus(id){
		console.log('kirim ajax dgn id');
	}
}
catch(err){
// 	 console.log('not table User?');console.log(err);
}

try{
	console.log('table agent');
	tableUsers=jQuery('#tableAgent').DataTable( {
		"columnDefs": [
            { 
				"render": function ( data, type, row ) {

                    return '<input type="button"  value="detail" onclick="detailAgent(\''+ row.main_email+'\',\''+ row.username+'\')" /><input type="button"  value="Edit" onclick="editUser(\''+ row.u_id+'\')" onclick2="editUser(\''+ row.main_email+'\',\''+ row.username+'\')" />';
					
                },
                "targets": 5
            }
		],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
		"order": [[ 0, "desc" ]],
        "columns": [
			{ "data": "created"},
			{ "data": "firstname","orderable": false},
            { "data": "username" },
            { "data": "email" },
            { "data": "accounttype","orderable": false },
            { "data": "action","orderable": false },             
        ],
		"lengthMenu": [
                [3,5, 10,15, 20, 35],
                [3,5, 10,15, 20, "max 35"] // change per page values here
            ],
    } );
	//console.log('table widtdrawal ready');
	function activeStatus(id){
		console.log('kirim ajax dgn id');
		params={id:id,type:"activeUserStatus"}
		url=urlChangeStatus;
		req=sendAjax(url,param);
		console.log(req);
	}
	function reviewStatus(id){
		console.log('kirim ajax dgn id');
	}
}
catch(err){
// 	 console.log('not table agent?');console.log(err);
}

try{
	console.log('table akun');
	tableUsers=jQuery('#table_account').DataTable( {
		"columnDefs": [
            { 
				"render": function ( data, type, row ) {

                    return '(in progress) <input type="button"  value="detail" onclick="detailAgent(\''+ row.main_email+'\',\''+ row.username+'\')" /><input type="button"  value="Edit" onclick="editUser(\''+ row.u_id+'\')" onclick2="editUser(\''+ row.main_email+'\',\''+ row.username+'\')" />';
					
                },
                "targets": 6
            }
		],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
        "columns": [
			{ "data": "created"},
			{ "data": "username","orderable": false},
            { "data": "email" },
            { "data": "accountid" },
            { "data": "agent","orderable": false },
            { "data": "type","orderable": false   },
            { "data": "action","orderable": false },             
        ],
		"lengthMenu": [
                [3,5, 10,15, 20, 35],
                [3,5, 10,15, 20, "max 35"] // change per page values here
            ],
		"order": [[ 0, "desc" ]]
    } );
	//console.log('table widtdrawal ready');
	function activeStatus(id){
		console.log('kirim ajax dgn id');
		params={id:id,type:"activeUserStatus"}
		url=urlChangeStatus;
		req=sendAjax(url,param);
		console.log(req);
	}
	function reviewStatus(id){
		console.log('kirim ajax dgn id');
	}
}
catch(err){
// 	 console.log('not table agent?');console.log(err);
}

try{
	console.log('table partner');
	tableUsers=jQuery('#tablePartner').DataTable( {
		"columnDefs": [
            { 
				"render": function ( data, type, row ) { 
				console.log(row);
                    return '<input type="button"  value="detail" onclick="detailUser('+ row.id+')" /><input type="button"  value="Edit" onclick="editUser(\''+ row.id+'\')" />';
					
                },
                "targets": 5
            }
		],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
        "columns": [
			{ "data": "created"},
			{ "data": "firstname","orderable": false},
            { "data": "username" },
            { "data": "email" },
            { "data": "accounttype","orderable": false },
            { "data": "action","orderable": false },             
        ],
		"lengthMenu": [
                [5, 10,15, 20, 35],
                [5, 10,15, 20, "max 35"] // change per page values here
            ],
    } );
	//console.log('table widtdrawal ready');
	function activeStatus(id){
		console.log('kirim ajax dgn id');
		params={id:id,type:"activeUserStatus"}
		url=urlChangeStatus;
		req=sendAjax(url,param);
		console.log(req);
	}
	function reviewStatus(id){
		console.log('kirim ajax dgn id');
	}
}
catch(err){
 	 console.log('not table patner?');
	console.log(err);
}

try{
	console.log('table user approval');
	tableUsers=jQuery('#tableApproval').DataTable( {
		"columnDefs": [
            { 
				"render": function ( data, type, row ) {
					console.log(row);
                    return '<input type="button"  value="detail and Approve" onclick="detailUser('+ row.id+')" /><input type="button"  value="Edit" onclick="editUser(\''+ row.id+'\')" />';
					
                },
                "targets": 6
            }
		],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
		"order": [[ 0, "desc" ]],
        "columns": [
			{ "data": "created","orderable": true},
			{ "data": "firstname","orderable": false},
            { "data": "username","orderable": false },
            { "data": "email" },
            { "data": "accounttype","orderable": false },
            { "data": "status", "orderable": true },     
            { "data": "action","orderable": false },             
        ],
		"lengthMenu": [
                [3,5, 10,15, 20, 35],
                [3,5, 10,15, 20, "max 35"] // change per page values here
            ],
		"pageLength": 5
    } );
	//console.log('table widtdrawal ready');
	function activeStatus(id){
		console.log('kirim ajax dgn id');
		params={id:id,type:"activeUserStatus"}
		url=urlChangeStatus;
		req=sendAjax(url,param);
		console.log(req);
	}
	function reviewStatus(id){
		console.log('kirim ajax dgn id');
	}
}
catch(err){
 	 console.log('not table User?');
	console.log(err);
}

try{	
	tableTop=jQuery('#tableSms').DataTable( {
		"columnDefs": [
            { 
			
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"lengthMenu": [
                [2,3,5, 10,15, 20, 35],
                [2,3,5, 10,15, 20, "max 35"] // change per page values here
            ],
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
        "columns": [
            { "data": "modified"},
            { "data": "tmp8"},
            { "orderable":      false,"data": "tmp1"},
            { "orderable":      false,"data": "msg" },
            { "orderable":      false,"data": "balance" },
            { "data": "tmp9" }           
        ]
    } );

    console.log('table SMS ready');
        tableLogs=jQuery('#tableSmsLogs').DataTable( {
		"columnDefs": [
            { 
			
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"lengthMenu": [
                [ 3,5, 10,15, 20, 35],
                [ 3,5, 10,15, 20, "max 35"] // change per page values here
            ],
        "ajax": {
            "url": urlAPILogs,
            "type": "POST"
        },
        "columns": [
            { "data": "tgl"},
            { "data": "status","orderable":      false},
            { "data": "jumlah","orderable":      false},           
        ],
        "dom": '<"top"i>rt<"bottom"flp><"clear">'
    } );

}
catch(err){
//	 console.log('not table Widtdrawal');
//	 console.log(err);
}


//====================BATCH EMAIL
try{	
    tableTop=jQuery('#tableEmail').DataTable( {
		"columnDefs": [
            { 
			
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"lengthMenu": [
                [2,3,5, 10,15, 20, 35],
                [2,3,5, 10,15, 20, "max 35"] // change per page values here
            ],
        "ajax": {
            "url": urlAPI,
            "type": "POST"
        },
        "columns": [
            { "data": "modified"},
            { "data": "tmp1"},
            { "data": "tmp2"},           
        ]
    } );
    
    tableLogs=jQuery('#tableEmailLogs').DataTable( {
		"columnDefs": [
            { 
			
            }
		],
		"order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"lengthMenu": [
                [2,3,5, 10,15, 20, 35],
                [2,3,5, 10,15, 20, "max 35"] // change per page values here
            ],
        "ajax": {
            "url": urlAPILogs,
            "type": "POST"
        },
        "columns": [
            { "data": "tgl"},
            { "orderable":      false,"data": "status"},
            { "orderable":      false,"data": "jumlah"},           
        ]
    } );
	//console.log('table widtdrawal ready');

}
catch(err){
//	 console.log('not table Widtdrawal');
//	 console.log(err);
}


try{
//history-table
tableUsers=jQuery('#history-table').DataTable({
	"lengthMenu": [
                [5, 10,15, 20, 35],
                [5, 10,15, 20, "max 35"] // change per page values here
            ],
});
	 console.log(' table history 2?');
}
catch(err){
 	 console.log('not table history 2?');
	console.log(err);
}

 	
} );
 
function detailUser(id){
	url=urlBase+"admin/detail_user/"+id;
	window.open(url);
	return true;
	params={id:id,type:"user_detail"}
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
		//console.log("success");	//console.log(result);			
	});
	respon.error(function(xhr,status,msg){			
			//console.log("Error");
			//console.log(status);
			//console.log(msg);
			//console.log(xhr);
			
	});
}

function changeAgent(email){
	url=urlBase+"admin/update_agent/"+email;
	window.open(url);
	return true;
}
function editUser(email){
	url=urlBase+"admin/edit_user/"+email;
	window.open(url);
	return true;
	params={email:email,type:"user_edit"}
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
		//console.log("success");	//console.log(result);			
	});
	respon.error(function(xhr,status,msg){			
			//console.log("Error");
			//console.log(status);
			//console.log(msg);
			//console.log(xhr);
			
	});
}
 
function detailAgent(email,accountid){
	params={email:email,type:"user_detail",accountid:accountid}
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
		//console.log("success");	//console.log(result);			
	});
	respon.error(function(xhr,status,msg){			
			//console.log("Error");
			//console.log(status);
			//console.log(msg);
			//console.log(xhr);
			
	});
}

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
		//console.log("success");	//console.log(result);			
	});
	respon.error(function(xhr,status,msg){			
			//console.log("Error");
			//console.log(status);
			//console.log(msg);
			//console.log(xhr);
			
	});
}

//===============DEPOSIT
function depositApprove(id){
	jQuery("#preview").html("<center><h2><i class='fa fa-5x fa-spinner fa-spin'></i> Please Wait.. Processing id:"+id+"<i class='fa fa-5x fa-spinner fa-spin'></i></h2></center>");
	params={status:"approve",id:id}
	url=urlData+"?type=depositProcess";
	respon=sendAjax(url,params);
	respon.success(function(result,status) {
		tableDeposit.draw();
		jQuery("#preview").html("");
		//console.log('approve');
	});
	
}

function depositCancel(id){
	jQuery("#preview").html("<center><h2><i class='fa fa-5x fa-spinner fa-spin'></i> Please Wait.. Processing id:"+id+"<i class='fa fa-5x fa-spinner fa-spin'></i></h2></center>");
	params={status:"cancel",id:id}
	url=urlData+"?type=depositProcess";
	respon=sendAjax(url,params);
	respon.success(function(result,status) {
		tableDeposit.draw();
		jQuery("#preview").html("");
		//console.log('cancel');
	});
	
}
//===============Widthdrawal
function widtdrawalApprove(id){
	jQuery("#preview").html("<center><h2><i class='fa fa-5x fa-spinner fa-spin'></i> Please Wait.. Processing id:"+id+"<i class='fa fa-5x fa-spinner fa-spin'></i></h2></center>");
	//console.log('approve');
	params={status:"approve",id:id}
	url=urlData+"?type=widtdrawalProcess";
	respon=sendAjax(url,params);
	respon.success(function(result,status) {
		tableWidtdrawal.draw();
		jQuery("#preview").html("");
	});
	
}

function widtdrawalCancel(id){
	jQuery("#preview").html("<center><h2><i class='fa fa-5x fa-spinner fa-spin'></i> Please Wait.. Processing id:"+id+"<i class='fa fa-5x fa-spinner fa-spin'></i></h2></center>");
	//console.log('cancel');
	params={status:"cancel",id:id}
	url=urlData+"?type=widtdrawalProcess";
	respon=sendAjax(url,params);
	respon.success(function(result,status) {
		tableWidtdrawal.draw();
		jQuery("#preview").html("");
	});
	
}

function sendAjax(url,params){
	jQuery("#bgAjax").show();
	var request = jQuery.ajax({
          url: url,
          type: "POST",
          data: params,
          dataType: "json", 
		  cache:false,
		  timeout:20000, 
    });
	request.success(function(){
		jQuery("#bgAjax").hide();
		//console.log("ajax end");
	});
	request.error(function(){
		jQuery("#bgAjax").hide();
		//console.log("ajax end");
	});
	
	return request;
}