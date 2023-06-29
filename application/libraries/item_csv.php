<?php
define("MEMORY_BUFFER_SIZE", 288);
define("FILENAME2","./csv/NEWTRIDENT.csv");

class item_csv{
	var $item_offset;
	var $CSV_BUFFER_ADDRESS=array();
	function read(){
		phpFastCache::$storage = "auto";
		$cache = phpFastCache();
		// $cache->clean(); // INCASE OF NOT LOADING THE RIGHT ITEM
		$this->item_offset = $cache->get("item_csv");
		 if($this->item_offset==null){
				
				$MAIN_STREAM=file(FILENAME2); 
				$FILE_HEADER = ExtractMemAddress($MAIN_STREAM[0]);
				
				if(MEMORY_BUFFER_SIZE!=getHeaderSize($FILE_HEADER)){
					echo "Unable to read this csv";
					exit(); 
				}
				$CSV_SIZE=getHeaderSize($MAIN_STREAM);

				for($a=1;$a<$CSV_SIZE;$a++){
					$data=ExtractMemAddress($MAIN_STREAM[$a]);
					$this->CSV_BUFFER_ADDRESS[$data[4]]["strName"]=$data[5];
					$this->CSV_BUFFER_ADDRESS[$data[4]]['strInventoryFile'] = $data[41];
                	$this->CSV_BUFFER_ADDRESS[$data[4]]['sICONID wMainID'] = $data[35];
                	$this->CSV_BUFFER_ADDRESS[$data[4]]['sICONID wSubID'] = $data[36];
				}
				$cache->set("item_csv",$this->CSV_BUFFER_ADDRESS,24300);
				$this->item_offset = $cache->get("item_csv");
		 }
		
	}
	
}


