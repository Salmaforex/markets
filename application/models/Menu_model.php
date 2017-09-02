<?php
class Menu_model extends CI_Model {

        public function __construct()
        {
            //$this->load->database();
        }
	function generate($target='home'){
		$menus=array();
		$data=array('name'=>'Home', 'href'=>'dashboard', 'title'=>'Dasboard');
		$menus[]=$data;
		$data=array('name'=>'Jmobile', 'href'=>'#', 'title'=>'Other');
		$data['subMenu'][]=array('name'=>'Jmobile', 'href'=>'jMobile','title'=>'Jquery Mobile');
		$data['subMenu'][]=array('name'=>'alto', 'href'=>'alto','title'=>'Alto Organizer');
		$data['subMenu'][]=array('name'=>'ciracas', 'href'=>'ciracas','title'=>'Farmasi Ciracas');
		$menus[]=$data;
		
		$data=array('name'=>'jqgrid', 'href'=>'#', 'title'=>'jQgrid');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid1', 'title'=>'load*');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid2', 'title'=>'grid*');		  
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid5', 'title'=>'edit input*');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid7', 'title'=>'edit Advance*');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid3', 'title'=>'advance 1'); 
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid4', 'title'=>'subgrid ');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid6', 'title'=>'advance 2');
		  
		  
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid8', 'title'=>'Map');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid9', 'title'=>'advance 3');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/jqgrid9', 'title'=>'group');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/demo', 'title'=>'versi 4');
		  $data['subMenu'][]=array('name'=>'js1', 'href'=>'js/demo2', 'title'=>'versi 3.8');
		  
		$menus[]=$data;
		
		$data=array('name'=>'stock0', 'href'=>'#', 'title'=>'Stock (old)');
		$data['subMenu'][]=array('name'=>'Category', 'href'=>'stock/category', 'title'=>'Category');
		$data['subMenu'][]=array('name'=>'Category2', 'href'=>'stock/listCategory', 'title'=>'Category (New)');
		$data['subMenu'][]=array('name'=>'product', 'href'=>'stock/products', 'title'=>'Product');
		$data['subMenu'][]=array('name'=>'location', 'href'=>'stock/location', 'title'=>'Location');
		$data['subMenu'][]=array('name'=>'report', 'href'=>'stock/report', 'title'=>'report');
		$data['subMenu'][]=array('name'=>'input', 'href'=>'stock/input', 'title'=>'Update Stock');
		
		$menus[]=$data;
		
		$data=array('name'=>'stock', 'href'=>'#', 'title'=>'Stock');
		$data['subMenu'][]=array('name'=>'Category2', 'href'=>'stock/listCategory', 'title'=>'Category');
		$data['subMenu'][]=array('name'=>'Category2', 'href'=>'stock/listProduct', 'title'=>'Product');
		$menus[]=$data;		
		
		$data=array('name'=>'bootstrap', 'href'=>'#', 'title'=>'Bootstrap');
		$data['subMenu'][]=array('name'=>'modal', 'href'=>'bootstrap/modal', 'title'=>'Modal');
		$menus[]=$data;
		
		
		$data=array('name'=>'helper', 'href'=>'#', 'title'=>'Guide');
		  $data['subMenu'][]=array('name'=>'Nasgor', 'href'=>'nasgor', 'title'=>'Nasgor');
		  $data['subMenu'][]=array('name'=>'db_helper', 'href'=>'guide/dbhelper', 'title'=>'Helper DB');
		  $data['subMenu'][]=array('name'=>'log_helper', 'href'=>'guide/loghelper', 'title'=>'Helper Log');
		  $data['subMenu'][]=array('name'=>'formbs_helper', 'href'=>'guide/bshelper', 'title'=>'form bs');
		  
		  $data['subMenu'][]=array('name'=>'my_libs', 'href'=>'guide/mylibs', 'title'=>'Libs');
		  $data['subMenu'][]=array('name'=>'log_libs', 'href'=>'guide/loglibs', 'title'=>'Log (libs)');
		  
		  
		  $data['subMenu'][]=array('name'=>'Admin2', 'href'=>'#', 'title'=>'Main Product');
		  $data['subMenu'][]=array('name'=>'Admin3', 'href'=>'#', 'title'=>'Product');
		
		$menus[]=$data;
		/*
		$data=array('name'=>'Report', 'href'=>'#', 'title'=>'Report');
		  $data['subMenu'][]=array('name'=>'report1', 'href'=>'#', 'title'=>'menu 1');
		  $data['subMenu'][]=array('name'=>'report2', 'href'=>'#', 'title'=>'menu 2');
		  $data['subMenu'][]=array('name'=>'Report3', 'href'=>'#', 'title'=>'menu 3');
		$menus[]=$data;
		
		
		
		$data=array('name'=>'log', 'href'=>'logout', 'title'=>'Log out');
		$menus[]=$data;
		*/
		//============
		foreach($menus	 as $id=>$menu){
			if(strtolower($menu['name'])==strtolower($target)){
				$menus[$id]['active']=true;
				$active=true;
			}else{}
			if(!isset($idFirst))$idFirst=$id;
		}
		
		if(!isset($active)){
			$menus[$idFirst]['active']=true;
		}
		return $menus;
	}
}