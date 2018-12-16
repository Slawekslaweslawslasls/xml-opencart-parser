<?php

include('./opencart-master/upload/config.php');

$db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (file_exists('./yandex_800463.xml')) {
	$xml_file = simplexml_load_file('./yandex_800463.xml');
	echo 'file yandex_800463.xml exist..<br>';

$language_id=1;

/*

$store_id=0;

// http://docs.opencart.com/en-gb/catalog/category/data/

foreach ($xml_file->shop->categories->category as $key) {
	
		//$level=1;
	    //echo $key." - ".$key{'id'}." - ".$key{'parentId'};
	   	$db->query("INSERT INTO ".DB_PREFIX."category SET `top`= 0, `column` = 1 , status = 1, date_modified = NOW(), date_added = NOW()");
	   	$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
	   	//echo "category id ".$db->insert_id;
	   	$generated=$db->insert_id;

	    $db->query("INSERT INTO ".DB_PREFIX."category_description SET category_id = LAST_INSERT_ID(), language_id = ".(int)$language_id.", name = '".(string)$key."', description = '".(string)$key."', meta_title = '".(string)$key."', meta_description = '".(string)$key."', meta_keyword = ''");
	    $db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
	    

	    $db->query("INSERT INTO ".DB_PREFIX."category_to_store SET category_id = LAST_INSERT_ID(), store_id = '" . (int)$store_id . "'");
	    $db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
		//'category_filter' skipped 
	    //'category_to_store' skipped
	    //i suggest there need arrange some seo-friendly metatags, i mean parse name into latin words
	    $db->query("INSERT INTO ".DB_PREFIX."seo_url SET store_id = LAST_INSERT_ID(), language_id = '".(int)$language_id."', keyword = '".translit((string)$key)."'");
	    $db->affected_rows<0? print "undone..<br>": print "done..<br>" ;


			    if ($key{'parentId'}==''){ 
			    	//$level=0;
			  		//echo "\n PRIMARY CATEGORY - ".$generated."\n";
					$db->query("UPDATE ".DB_PREFIX."category SET `parent_id`=".$generated." WHERE category_id=".$generated);
					$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
					//main categories
					$db->query("INSERT INTO ".DB_PREFIX."category_path SET `category_id`=".$generated.",`path_id` = ".$generated.", `level` =0");
					$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
					$db->query("UPDATE ".DB_PREFIX."category_path SET `level` = 0 WHERE `path_id` = ".$generated);
					$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
					$db->query("UPDATE ".DB_PREFIX."seo_url SET  query = CONCAT('path=',".$generated."), push = CONCAT('route=product/category&path=',".$generated.") WHERE store_id=".$generated);	
					$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
					$generated_stop_count=$generated;
					
			    }

		$db->query("UPDATE ".DB_PREFIX."category SET `parent_id`=".$generated_stop_count);
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
		//firest query related to relations between entities, the second one exactly entities (subcategories)
		$db->query("INSERT INTO ".DB_PREFIX."category_path SET `category_id` = ".$generated.",`path_id` = ".$generated.",`level` = 1");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;

		$db->query("INSERT INTO ".DB_PREFIX."category_path SET `category_id` = ".$generated.",`path_id` = ".$generated_stop_count.",`level` = 0");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;

		$db->query("UPDATE ".DB_PREFIX."seo_url SET  query = CONCAT('path=',".$generated_stop_count.",'_', ".$generated."), push = CONCAT('route=product/category&path=',".$generated_stop_count.",'_', ".$generated.") WHERE query=''");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;
		$db->query("UPDATE ".DB_PREFIX."seo_url SET  store_id=0");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;

	
		unset($key);
		 }



*/



$quantity=1;
$store_id=0;
$product_attribute=0;


	foreach($xml_file->shop->offers->offer as $key_offer){
		$i++;
			foreach($key_offer->param as $key_param){
					$preg_size=preg_match_all("/размеры/ium",$key_param['name']);
						if($preg_size>0){
							$size=$key_param;				
							preg_replace('/\s\s+/','', $size);
							$size=explode( 'х', $size);
						}

					$preg_weight=preg_match_all("/Масса, кг/ium",$key_param['name']);
						if($preg_weight>0){				
							$weight=$key_param;			
							preg_replace('/\s\s+/','', $weight);
						}
					$all_params.=$key_param['name']." - ".$key_param." ";

			}

		$db->query("INSERT INTO ".DB_PREFIX."product SET model = '" . $key_offer{'id'}. "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '" . (int)$quantity. "', minimum = '', subtract = '', stock_status_id = '', date_available = '" .date('o-m-d') . "', manufacturer_id = '" . (int)$i . "', shipping = '', price = '" . (float)$key_offer{'price'} . "', points = '', weight = '" .(float)$weight."', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$size[0] . "', width = '" . (float)$size[1] . "', height = '".$size[2]."', length_class_id = '', status = 1, tax_class_id = 9, sort_order = 0, date_added = NOW(), date_modified = NOW()");
		$generated_product_id=$db->insert_id;
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;



		$db->query("INSERT INTO ".DB_PREFIX."product_description SET product_id = '" . (int)$generated_product_id . "', language_id = 1, name = '" . $key_offer->name. "', description = '" . $key_offer->description. "', tag = '" .translit($key_offer->name). "', meta_title = '".translit($key_offer->name)."', meta_description = '" . translit($key_offer->description). "', meta_keyword = '" .translit($key_offer->name). "'");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;

		$db->query("INSERT INTO ".DB_PREFIX."product_to_store SET product_id = '" .$generated_product_id. "', store_id = '" . (int)$store_id . "'");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;

		$db->query("INSERT INTO ".DB_PREFIX."product_attribute SET product_id = '".$generated_product_id."', attribute_id = '" .(int)$product_attribute."', language_id = '" .$language_id. "', text = '" .$all_params. "'");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;	

		
		$db->query("INSERT INTO ".DB_PREFIX."product_to_layout SET product_id = '" .$generated_product_id. "', store_id = '" .$store_id. "', layout_id = 0");
		$db->affected_rows<0? print "undone..<br>": print "done..<br>" ;


unset($key_param,$key_offer);

}

$db->close();

}
else{
	echo "file yandex_800463.xml doesn't exist";
}

//$blocks  = $xml->xpath('//block'); //gets all <block/> tags



function translit($s) {
		  $s = (string) $s; 
		  $s = strip_tags($s); 
		  $s = str_replace(array("\n", "\r"), " ", $s); 
		  $s = preg_replace("/\s+/", ' ', $s); 
		  $s = trim($s); 
		  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
		  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
		  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); 
		  $s = str_replace(" ", "_", $s); 
  return $s; 
}




/*
DELETE FROM `oc_seo_url`;
DELETE FROM `oc_category_description`;
DELETE FROM `oc_category_path`;
DELETE FROM `oc_category`;
DELETE FROM `oc_category_to_store`;

DELETE FROM `oc_product` WHERE product_id>55;
DELETE FROM `oc_product_description` WHERE product_id>55;
DELETE FROM `oc_product_attribute`;

*/
?>