<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$rand_num= 8;//rand(1000, 9000);
?>
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
<?php
} 
?>
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
                <a class="inlineblock vmargin-15 bright" href="https://www.salmamarkets.com/privacy-policy/#">Privacy Policy</a>
                <a class="inlineblock vmargin-15 bright" href="https://www.salmamarkets.com/terms-conditions/#">Terms & Conditions</a>
            </div>
            <div class="col-md-4 text-right hpadding-20 visible-md visible-lg small">
            	Copyright @ Salma Forex 2017 All Rights Reserved
            </div>
            <div class="col-md-12 small opacity-5">
            	<p><strong>RISK WARNING:</strong> Trading foreign exchange, foreign exchange options, foreign exchange forwards, contracts for difference, bullion and other over-the-counter products carries a high level of risk and may not be suitable for all investors. Past performance of an investment is no guide to its performance in the future. Investments, or income from them, can go down as well as up. You may not necessarily get back the amount you invested.</p>
                <p>All opinions, news, analysis, prices or other information contained on this website are provided as general market commentary and does not constitute investment advice, nor a solicitation or recommendation for you to buy or sell any over-the-counter product or other financial instrument.</p>
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


<?php 
if(isset($footerJS)){ 
	if(!is_array($footerJS)){ 
		$footerJS=array($footerJS); 
	}else{}
	
	foreach($footerJS as $jsFile ){?>
	  <script src="<?=base_url();?>media/<?=$jsFile;?>?r=<?=$rand_num;?>"></script>
<?php 
	}
}else{ echo '<!--no footer js -->'; } ?>
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

</body>
</html>