<meta charset="utf-8">
<title><?php 
if(isset($title)){ 
	echo $title;
}
else{
?>HELLO WORLD<?php 
} ?></title> 

<?php 
if(isset($fileCss)){
	foreach($fileCss as $id=>$file){
		if(intval($id)==0){ $strID='id="'.$id.'"';}else{ $strID='';}
?>
	<link rel="stylesheet" <?=$strID;?> href="<?=base_url().'media/'.$file;?>?i=8"  media='all' />
<?php
	}
}
?>
	<!--[if IE 7]>
	<link rel='stylesheet' id='theme-fontawesome-ie7-css'  href='<?=base_url();?>media/css/module.fontawesome/source/css/font-awesome-ie7.min.css?ver=384753e655020ba892b1123f6ddf06b2' type='text/css' media='all' />
	<![endif]-->
<?php 
if(isset($fileJs)){
	foreach($fileJs as $file){?>
	<script src="<?=base_url().'media/'.$file;?>"></script><?php
	}
}

?>
<meta name="description" content="<?=$description;?>" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87922681-1', 'auto');
  ga('send', 'pageview');

</script>