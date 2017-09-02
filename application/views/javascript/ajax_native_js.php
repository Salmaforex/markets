function ajaxGetShowId(url, targetId, async=true,debug=0){
	var xhttp=xmlHttp();
	
	if(async!=true)async=false;
	xhttp.open("GET", url, async);
	xhttp.onreadystatechange = function () {
		if(debug==1){
			console.log(xhttp);
		}
		if (xhttp.readyState != 4 || xhttp.status != 200){
			return 0;
		}
		else{
			document.getElementById(targetId).innerHTML=xhttp.responseText;
		}
	};
	xhttp.send();
/*bila TIDAK menggunakan async maka bisa mengembalikan hasil*/
	response={detail:xhttp, text:xhttp.responseText, state:xhttp.readyState, status:xhttp.status, url:xhttp.responseURL, type:xhttp.responseType}
	return response;
}

function ajaxGetJson(url,async=false, debug=0){
	var xhttp=xmlHttp();
	
	if(async!=true)async=false;
	xhttp.open("GET", url, async);
	xhttp.onreadystatechange = function () {
		if(debug==1){
			console.log(xhttp);
		}
		if (xhttp.readyState != 4 || xhttp.status != 200){
			return 0;
		}
	};
	xhttp.send();
	response=xhttp.responseText;
	json=JSON.parse(response);
	return json;
}

function xmlHttp(){
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	//new ActiveXObject("MSXML2.XMLHTTP.3.0")	
	}
	return xhttp;
}

function ajaxPostJson(url,data=array(),async=false, debug=0){
	var xhttp=xmlHttp();
	
	if(async!=true)async=false;
	xhttp.open("POST", url, async);
//	xhttp.setRequestHeader('Content-Type', 'application/json');
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhttp.onreadystatechange = function () {
		if(debug==1){
			console.log(xhttp);
		}
		if (xhttp.readyState != 4 || xhttp.status != 200){
			return 0;
		}
	};
	params= param(data); console.log(params);
	xhttp.send( params );//JSON.stringify(data) );
	response={state:xhttp.readyState, status:xhttp.status, url:xhttp.responseURL, type:xhttp.responseType}
	text=xhttp.responseText;
	try {
		json=JSON.parse(text);
		response.result=json;
		response.error=false;
		response.message='OK';
	}
	catch(err) {
		response.result=false;
		response.error=err;
		response.message=err.message;
	}
	return response;
}


//=================
function param(object) {
    var encodedString = '';
    for (var prop in object) {
        if (object.hasOwnProperty(prop)) {
            if (encodedString.length > 0) {
                encodedString += '&';
            }
            encodedString += encodeURI(prop + '=' + object[prop]);
        }
    }
    return encodedString;
}
