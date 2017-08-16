<?php
if(!defined('LOCAL')){
/*
?>
<script type="text/javascript" src="https://secure.skypeassets.com/i/scom/js/skype-uri.js"></script>
<div id="SkypeButton_Call_sfx.partnership_1">
 <script type="text/javascript">
 Skype.ui({
 "name": "dropdown",
 "element": "SkypeButton_Call_sfx.partnership_1",
 "participants": ["sfx.partnership"]
 });
 </script>
</div>
<?php
*/
}
else{?>
	<script type="text/javascript">
	$(document).ready(function(e) {
	$(".div_support").hide();
	$("#frmLiveAccount").css('padding','20px 20px').css('margin','30px');
	//console.log('hide'); css('background-color','#1262BD').
	});
	</script>
<?php
}
?>
	<script type="text/javascript">
	$(document).ready(function(e) {
	$(".div_support").hide();
	$("#frmLiveAccount").css('padding','20px 20px').css('margin','30px');
	//console.log('hide'); css('background-color','#1262BD').
	});
	</script>

	<script type="text/javascript">
try{
	ddaccordion.init({
		headerclass: "submenuheader", //Shared CSS class name of headers group
		contentclass: "submenu", //Shared CSS class name of contents group
		revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
		mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
		collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
		defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
		onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
		animatedefault: false, //Should contents open by default be animated into view?
		persiststate: true, //persist state of opened contents within browser session?
		toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
		togglehtml: ["suffix", "<img src='plus.gif' class='statusicon' />", "<img src='minus.gif' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
		animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
		oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
			//do nothing
		},
		onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
			//do nothing
		}
	})
}
catch(err) {
    console.log(err );
}
</script>