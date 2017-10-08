<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html>
	<head>
	<meta name="google-site-verification" content="eN2buGvr_kmbf3GhbcXebqC2slK0av-S5qfviGM9mys" />
<?php 
    $load_view=isset($baseFolder)?$baseFolder.'inc/head_view':'head_view';
    $this->load->view($load_view);
	  if(!defined('LOCAL')){
?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
<?php } ?>
		<link rel="shortcut icon" href="<?=$shortlink;?>media/img/salmaforex.png" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87922681-1', 'auto');
    ga('send', 'pageview');
 
 </script>
	</head>
<body>
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/menu_view':'menu_view';
	$this->load->view($load_view);
?>
<?php 
	$load_view=isset($baseFolder)?$baseFolder.'inc/header_view':'header_view';
	$this->load->view($load_view);
?>  
<div class="middle <?=!isset($noBG)?'fullbg':'';?>">
<?php 
	$aContent= !is_array($content) ?(array)$content:$content;
	foreach($aContent as $load_view){
		$this->load->view($baseFolder.'contents/'.$load_view."_view");
	}
?>
</div>
<div class="footer">
	<div class="vspace-30"></div>
	<div class="container">
    	<div class="row">
        	<div class="col-md-8">
            	<img alt="Salma Forex Logo" src="<?=base_url();?>media/images/logo1.png" />
                <a class="inlineblock vmargin-15 bright" href="https://www.salmamarkets.com/privacy-policy/">Privacy Policy</a>
                <a class="inlineblock vmargin-15 bright" href="https://www.salmamarkets.com/terms-conditions/#">Terms & Conditions</a>
            </div>
            <div class="col-md-4 text-right hpadding-20 visible-md visible-lg small">
            	Copyright @ Salma Forex 2017 All Rights Reserved
            </div>
            <div class="col-md-12 small opacity-5">
            	<p class='hide'> <strong>RISK WARNING:</strong> Trading foreign exchange, foreign exchange options, foreign exchange forwards, contracts for difference, bullion and other over-the-counter products carries a high level of risk and may not be suitable for all investors. Past performance of an investment is no guide to its performance in the future. Investments, or income from them, can go down as well as up. You may not necessarily get back the amount you invested.</p>
                <p class='hide'>All opinions, news, analysis, prices or other information contained on this website are provided as general market commentary and does not constitute investment advice, nor a solicitation or recommendation for you to buy or sell any over-the-counter product or other financial instrument.</p>
                <div class="vspace-15"></div>
                <hr/>
            </div>
        </div>
    </div>
    <div class="vspace-30"></div>
</div>
<div class="bottom-footer">
	<div class="container">
	<p class="text-left small bright opacity-5"><strong>Salma Forex Partners</strong> - The leading forex broker @ 2017</p>
    </div>
</div>

<div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="codeModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Your Code</h4>
      </div>
      <div class="modal-body">
        <div class="well no-margin code-container"><samp class="theCode"></samp></div>
      </div>
      <div class="modal-footer">
	  Click button to copy HTML into clipboard.
        <button class="zclip btn btn-primary btn-sm">Copy HTML</button>
      </div>
    </div>
  </div>
</div>
<?php 
if(isset($footerJS)){ 
	if(!is_array($footerJS)){ 
		$footerJS=array($footerJS); 
	}else{}
	
	foreach($footerJS as $jsFile ){?>
	  <script src="<?=base_url();?>media/<?=$jsFile;?>?p=7"></script>
<?php 
	}
}else{ echo '<!--no footer js -->'; } 

if(isset($outerJS)){
	foreach($outerJS as $jsFile ){?>
	  <script src="<?=$jsFile;?>?p=8"></script>
<?php 
	}
}
?>
<?php 
if(!defined('LOCAL')){?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5652d61400c5a4a1546218c3/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<?php 
}

$load_view=isset($baseFolder)?$baseFolder.'inc/footer_view':'footer_view';
		$this->load->view($load_view);
?>
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
</body>
</html>