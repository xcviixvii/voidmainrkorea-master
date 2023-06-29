<style>
     .necklace{
        background:url(<?=base_url()?>commercialset02_3_12.png) -1px -1px;
        /* FOR COORDINATES THE ITEM 35 * Coordinate X  + 1 */
        /* FOR COORDINATES THE ITEM 35 * Coordinate Y  + 1 */

    }
    
    .sprite-icon{
        height:33px;
        width:33px;
        opacity: 0.7;
        border:1px solid #000;
    }
    .sprite-icon:hover{
        opacity: 1; 
        cursor:pointer;
    }
    .clearfix{
        clear:both;
    }

   

    .item-icon-holder{
	padding:2px;
	margin:1px solid red;
	width:33px;
	height:33px;
	
    }
    .item-image{
        width:33px;
        height:33px;
        border:1px solid #666;
        background:#000; /*empty box */
        cursor:pointer;
    }

    .item-image2{
        width:33px;
        height:33px;
        border:1px solid #666;
        background:#888; /*empty box */
        cursor:pointer;
    }
    .item-image:hover{
        border:1px solid #1e90ff;
    }

    .locker_inventory{
        border:1px solid #ccc;
        width:225px;
        height:375px;
        padding-left:2px;
    }
    .locker_value_holder{
        border:1px solid #ccc;
        width:347px;
        height:375px;
        padding-left:10px;
        margin-left:3px;
    }

    .rfloat{
        float:right;
    }
    .lfloat{
        float:left;
    }

</style>
<?php

$Binary = '0x14010000500000000C0000000000000020C939150D000000FFFFFFFFC3FFDE5E000000000000000000000000000000000000000096000100000000000000000000000000000000000000000000000000000000000000005E00000000000001006600740018000400FFFFFFFF0700DF5E0000000000000000000000000D00000000000000010002000000090000000000000000000000000000000000000000000000000000000000000000000000020020C9391576001800FFFFFFFF0700DF5E00000000000000000000000029000000000000000F0002000000000000000000000000000000000000000000000000000000000000000000000000000100000020C9391504000400FFFFFFFFC3FFDE5E00000000000000000000000000000000000000000A000100000000000000000000000000000000000000000000000000000000000000005E0000000001000100C8B9AB1C18003E00FFFFFFFF8056E05E0000000000000000000000003500000000000000010002000000000000000000000000000000000000000000000000000000000000000000000000000200000000000000B8001B00FFFFFFFFC3FFDE5E000000000000000000000000000000000000000000000100000000000000000000000000000000000000000000000000000000000000005E000000000200010020C9391575000500FFFFFFFF0700DF5E00000000000000000000000029000000000000000A000200000000000000000000000000000000000000000000000000000000000000000000000000030000000000000018000200FFFFFFFF0700DF5E0000000000000000000000000D0000000000000001000200000009000000000000000000000000000000000000000000000000000000000000000000030001003F005C0075000E00FFFFFFFF0700DF5E00000000000000000000000029000000000000000F0002000000000000000000000000000000000000000000000000000000000000000000000000000400000020C939151D000100FFFFFFFF00000000000000000000000000000000000000000000000000000100000000000000000000000000000000000000000000000000000000000000000B00000000040001000000000075001E00FFFFFFFF0700DF5E00000000000000000000000029000000000000000F000200000000000000000000000000000000000000000000000000000000000000000000000000050001003F005C0075001200FFFFFFFF0700DF5E00000000000000000000000029000000000000000F000200000000000000000000000000000000000000000000000000000000000000000000000000';

$chainven = bin2hex($Binary);

$item=array();

$INVEN_HEAD = substr($Binary,0,26);    //INVENTORY HEADER
$INVEN_SIZE = str_ireplace($INVEN_HEAD,"",$Binary); // REMOVE INVENTORY HEADER
$FILE_SIZE = strlen($INVEN_SIZE);
$HEX = bin2hex($INVEN_SIZE);
$MEM_SIZE= 160;//(getData($INVEN_HEAD,0,2) == 20) ? 176 :160;
$ITEM_LIST = str_split($INVEN_SIZE,$MEM_SIZE);



if($ITEM_LIST!=0){
    $Items = "";
    $Out = "";
    $ItemIco = "";
    $counter = 0;
    foreach($ITEM_LIST as $em_Buffer){
        // $item['tab1']['MEM'][]=$em_Buffer;
		$MID=str_pad(trimID($em_Buffer,16,4),3,0,STR_PAD_LEFT);
		$SID=str_pad(trimID($em_Buffer,20,4),3,0,STR_PAD_LEFT);
        $address="IN_".$MID."_".$SID;
        $item['tab1']['address'][]="IN_".$MID."_".$SID;
		$item['tab1']['pos'][]="x".getData($em_Buffer,0,2)."y".getData($em_Buffer,4,2).'<br />';
		$item['tab1']['X'][]=getData($em_Buffer,0,2);
		$item['tab1']['Y'][]=getData($em_Buffer,4,2);
        $item['tab1']['MID'][]=$MID;
        $item['tab1']['SID'][]=$SID;
        // $item['tab1']['ItemName'][]=read($address,"strName");
        // $item['tab1']['InventoryFile'][]=read($address,'strInventoryFile');
        // $item['tab1']['ICONX'][]=read($address,'sICONID wMainID');
        // $item['tab1']['ICONY'][]=read($address,'sICONID wSubID');
        // $item['tab1']['QTY'][]=getOffset($em_Buffer,82,80);
        // $item['tab1']['DMG_GRADE'][]=getData($em_Buffer,90,2);//+damage upgrade///
		// $item['tab1']['DEF_GRADE'][]=getData($em_Buffer,92,2); //+defense upgrade//
		// $item['tab1']['ELEC'][]=getData($em_Buffer,98,2); //+elec upgrade//
		// $item['tab1']['FIRE'][]=getData($em_Buffer,94,2); //+fire upgrade//
		// $item['tab1']['ICE'][]=getData($em_Buffer,96,2); //+ice upgrade//
		// $item['tab1']['POISON'][]=getData($em_Buffer,100,2); //+poison upgrade//
		// $item['tab1']['WIND'][]=getData($em_Buffer,102,2); //+wind upgrade//
		// $item['tab1']['Opt1'][]=getData($em_Buffer,104,2);
		// $item['tab1']['Opt2'][]=getData($em_Buffer,108,2);
		// $item['tab1']['Opt3'][]=getData($em_Buffer,106,2);
		// $item['tab1']['Opt4'][]=getData($em_Buffer,110,2);
		// $item['tab1']['Val1'][]=getOffset($em_Buffer,114,112);
		// $item['tab1']['Val2'][]=getOffset($em_Buffer,122,120);
		// $item['tab1']['Val3'][]=getOffset($em_Buffer,118,116);
        // $item['tab1']['Val4'][]=getOffset($em_Buffer,126,124);
        
        // echo $address.' : '.read($address,'strName').' : '.read($address,'strInventoryFile').' : '.read($address,'sICONID wMainID').' : '.read($address,'sICONID wSubID');

        // echo '<div class="sprite-icon" style="background:url('.base_url().'slot/'.str_ireplace(".dds","",read($address,'strInventoryFile')).'_'.read($address,'sICONID wSubID').'_'.read($address,'sICONID wMainID').'.png) -1px -1px;"><div class="clearfix"></div></div>';

        // echo ':'.str_ireplace(".dds","",read($address,'strInventoryFile')).'_'.read($address,'sICONID wSubID').'_'.read($address,'sICONID wMainID').'.png';
        // echo '<br />';
        $Items .= 'x'.getData($em_Buffer,0,2)."y".getData($em_Buffer,4,2).',';
        $ItemIco .= $address.',';
        $counter++;
        
    }

}

// echo read("IN_013_000",'strName');


// $CSV_BUFFER_ADDRESS=array();
//     for($a=1;$a<count($ITEM_LIST) - 1;$a++){
//         $data=ExtractMemAddress($item[$a]);
//         echo $data[5];
//     }
    
echo '<pre>';
// print_r(array_values($item['tab1']['address']));
// print_r(array_values($item['tab1']['pos']));
// print_r(array_values($item['tab1']['ItemName']));
echo '</pre>';
// for ($d=0; $d<$counter; $d++){
    //     foreach ($item as $val) {
    //         echo $val['pos'][$d];
    //     } 
    // }
 

    $itemxy = explode(",",rtrim($Items,','));
    if($itemxy){
        $itemxyz = array();
            foreach ($itemxy as $itemval) {
                $itemxyz[] .= $itemval;
            }
        } else {
            $itemxyz = array();
        }
 
echo "<div class='locker_inventory lfloat'>";
$strval = "";
for ($y =0; $y < 10; $y++){
    for ($x = 0; $x < 6; $x++){
        $val = 'x'.$x.'y'.$y;
        echo FindItem($val);
    }
    echo '<div class="clearfix"></div>';
}
echo '</div>';





            // $elementzz="<div class='locker_inventory lfloat'>";
            // $itmz = "";
            // for($a=0;$a<10;$a++){
            //     // echo 'x0y'.$a.'';
            //     // for ($d=0; $d<$counter; $d++){
            //         foreach ($item as $itmz) {
            //             if('x0y'.$a.''==$itmz['pos'][0]){
            //             echo $itmz['pos'][0];
            //             }
            //         } 
            //     // }
            //     // $elementzz.="<div id='x0y".$a."' class='item-icon-holder lfloat'><div id='Litem3'  data-ids='NA' data-c_x='0' data-c_y=".$a." class='item-image'></div></div>";
            //     // $elementzz.="<div id='x1y".$a."' class='item-icon-holder lfloat'><div id='Litem3'  data-ids='NA' data-c_x='1' data-c_y=".$a." class='item-image'></div></div>";
            //     // $elementzz.="<div id='x2y".$a."' class='item-icon-holder lfloat'><div id='Litem3'  data-ids='NA' data-c_x='2' data-c_y=".$a." class='item-image'></div></div>";
            //     // $elementzz.="<div id='x3y".$a."' class='item-icon-holder lfloat'><div id='Litem3'  data-ids='NA' data-c_x='3' data-c_y=".$a." class='item-image'></div></div>";
            //     // $elementzz.="<div id='x4y".$a."' class='item-icon-holder lfloat'><div id='Litem3'  data-ids='NA' data-c_x='4' data-c_y=".$a." class='item-image'></div></div>";
            //     // $elementzz.="<div id='x5y".$a."' class='item-icon-holder lfloat'><div id='Litem3'  data-ids='NA' data-c_x='5' data-c_y=".$a." class='item-image'></div></div><div class='clearfix'></div>";
            // }


            
?>

