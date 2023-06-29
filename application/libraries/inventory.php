<?php

class inventory {
    var $item_offset;
    var $ITEM_BUFFER=array();
    var $item=array();

	function GenItem($loc,$head){
        phpFastCache::$storage = "auto";
        $cache = phpFastCache();

        $csv = new item_csv;
        $csv->read();
        $Binary = ChaInven();
        
        // $cache->clean(); // INCASE OF NOT LOADING THE RIGHT ITEM

        $chainven = $Binary;
        $INVEN_HEAD = substr($Binary,0,24);    //INVENTORY HEADER
        $INVEN_SIZE = str_ireplace($INVEN_HEAD,"",$Binary); // REMOVE INVENTORY HEADER
        $FILE_SIZE = strlen($INVEN_SIZE);
        $MEM_SIZE= 160;
        $ITEM_LIST = str_split($INVEN_SIZE,$MEM_SIZE);

        
            if($ITEM_LIST!=0){
                foreach($ITEM_LIST as $em_Buffer){
                    $MID=str_pad(trimID($em_Buffer,16,4),3,0,STR_PAD_LEFT);
                    $SID=str_pad(trimID($em_Buffer,20,4),3,0,STR_PAD_LEFT);
                    $address="IN_".$MID."_".$SID;
                    $item[] = array(  
                        'pos' => "x".getData($em_Buffer,0,2)."y".getData($em_Buffer,4,2),
                        'address' => "IN_".$MID."_".$SID,
                        'strName' => $csv->item_offset[$address]['strName'],
                        'InventoryFile' => $csv->item_offset[$address]['strInventoryFile'],
                        'ICONX' => $csv->item_offset[$address]['sICONID wMainID'],
                        'ICONY' => $csv->item_offset[$address]['sICONID wSubID']
                    );
                }
            }

            
            for($a=0;$a<count($ITEM_LIST);$a++){
                $data=$item[$a];
                $ITEM_BUFFER[$data['pos']]['pos'] = $data['pos'];
                $ITEM_BUFFER[$data['pos']]['strName'] = $data['strName'];
                $ITEM_BUFFER[$data['pos']]['InventoryFile'] = $data['InventoryFile'];
                $ITEM_BUFFER[$data['pos']]['ICONX'] = $data['ICONX'];
                $ITEM_BUFFER[$data['pos']]['ICONY'] = $data['ICONY'];
            }
            return @$ITEM_BUFFER[$loc][$head];
        
    
    }
}
