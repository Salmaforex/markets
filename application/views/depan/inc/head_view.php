<?php
$rand_num= 8;//rand(1000, 9000);
?><meta charset="utf-8">
<title><?php 
if(isset($title)){ 
	echo $title;
}
else{
?>Salmaforex<?php 
} ?></title> 

<?php 
if(isset($fileCss)){
	foreach($fileCss as $id=>$file){
		if(intval($id)==0){ $strID='id="'.$id.'"';}else{ $strID='';}
?>
	<link rel="stylesheet" <?=$strID;?> href="<?=base_url().'media/'.$file;?>?i=<?=$rand_num;?>"  media='all' />
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
	<script src="<?=base_url().'media/'.$file;?>?p=<?=$rand_num;?>"></script><?php
	}
}

?>

<meta name="description" content="<?=$description;?>" />