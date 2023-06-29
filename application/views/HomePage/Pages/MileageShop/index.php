<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?>  | Mileage Shop</title>
<body  class="sub-body">
<div id="dialog" style='display:none; text-align:center; overflow:hidden;' >
</div>
    <div class="in-body4">
        <div class="bottom-body">
            <div id="wrapper">
                <?php
				renderview('HomePage/Templates/tmpheader');
				?>
                <div id="container">
                    <nav id="nav">                
                        <section id="left-nav">&nbsp;</section>
                   
        
                    </nav>
                    <section id="content">
                        

                        
    <section class="page-history">
		HOME<img src="<?=base_url()?>Images/Icon/navi_icon.gif" alt="">
		<span class="page-history-select">MILEAGE SHOP</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/Itemshop/mileageshop_sub_title.gif" alt="ItemShop">
	</h3>
	
	
	<fieldset class="item-search">
		<legend>Item Search</legend>
		<section>
			<div class="search-title">
				<img src="<?=base_url()?>Images/ItemshopNew/item_search.gif" alt="Find Item">
			</div>
			<div class="left">
				<input type="text" id="txtSearchString" name="txtSearchString" class="input-text">
				<img src="<?=base_url()?>Images/Button/search_btn.gif" id="btnSearch" class="pointer" alt="Search">
            </div>
            
			<div class="clear"></div>
		</section>
	</fieldset>
	<section class="item-list"><!-- 한 페이지에 12개 -->
		<header>
			<h4>All</h4>
			<div class="left">
				<p class="item-count">
					<strong><?=$count;?></strong> Registered Items
				</p>
			</div>
			<div class="right">
            
    
             				
			</div>
			<div class="clear"></div>
		</header>
	
	<?php
    $i = 1;
    echo '<ul>';
    foreach (array_chunk($MileageShop, 4, true) as $array) {
        
        foreach($array as $item) {
            $prod1 = $this->llllllllz_model->getshopcfg($item['ProductNum']);
            if($item['ItemSS'] == ""){
                $source = base_url().'Images/ItemShopNew/Default.jpg';
            } else {
                $source = base_url().'Uploadfiles/ItemShop/'.$item['ItemSS'];
            }

            $string = strip_tags($item['ItemName']);
            $itemname = substr($string,0,15).''.((strlen($item['ItemName']) >15) ? "...":"").'';
            if($prod1){
                if($prod1[0]['ribbon'] == 1){
                    $figure = 'limited-item';
                    $sticker = '<div class="sticker"><img src="'.base_url().'Images/ItemshopNew/limited_sticker.png" alt="Limited Edition"></div>';
                } elseif($prod1[0]['ribbon'] == 2) {
                    $figure = 'discount-item';
                    $sticker = '<div class="sticker"><img src="'.base_url().'Images/ItemshopNew/discount_sticker.png" alt="Discounted"></div>';
                } elseif($prod1[0]['ribbon'] == 3){
                    $figure = 'event-item';
                    $sticker = '<div class="sticker" style="z-index:1"><img src="'.base_url().'Images/ItemshopNew/event_sticker.png" alt="event"></div>';
                }

                if($prod1[0]['discount'] != NULL){
                    $originalprice = $item['ItemPrice'];
                    $discount = $item['ItemPrice'] * ($prod1[0]['discount'] / 100);
                    $dc = '<span>(<em>'.$prod1[0]['discount'].'%</em>)</span>';
                    $price =  $item['ItemPrice'] - $discount;
                } else {
                    $originalprice = "";
                    $dc = "";
                    $price = $item['ItemPrice'];
                }

                if($prod1[0]['ItemSub'] != NULL){
                    $itemN = $this->llllllllz_model->getitemebyitem($prod1[0]['ItemSub']);
                    if($itemN[0]['ItemSS'] == ""){
                        $sourcesub = base_url().'Images/ItemShopNew/Default.jpg';
                    } else {
                        $sourcesub = base_url().'Uploadfiles/ItemShop/'.$itemN[0]['ItemSS'];
                    }
                    $wrapper = '<div class="item-wrapper2_box">
                    <div class="item-wrapper2">
                        <img src="'.$source.'" id="'.$item['ProductNum'].'" class="item-image pointer" alt="'.$item['ItemName'].'">
                    </div>

                    <div class="item-wrapper3">
                        <div class="item-wrapper3_plus"><img src="'.base_url().'Images/ItemshopNew/icon_plus.gif" alt=""></div>
                        <img src="'.$sourcesub.'" id="Img2" alt="'.$itemN[0]['ItemName'].'">
                    </div>
                </div>';
                } else {
                    $wrapper = '<div class="item-wrapper"><img src="'.$source.'" id="'.$item['ProductNum'].'" class="item-image pointer" alt="'.$item['ItemName'].'"></div>';
                }
            } else {
                $figure = "";
                $sticker = "";
                $originalprice = "";
                $dc = "";
                $price = $item['ItemPrice'];
                $wrapper = '<div class="item-wrapper"><img src="'.$source.'" id="'.$item['ProductNum'].'" class="item-image pointer" alt="'.$item['ItemName'].'"></div>';
            }





            if($i > 0 && $i % 4 == 0) {
                echo '<li class="last">
                    <figure class="'.$figure.'">
                        '.$sticker.'

                        '.$wrapper.'
                        
                        
                        <figcaption>'.$itemname.'</figcaption>
                        <p class="orignal-price">'.$originalprice.'</p>
                        <div class="sale-price">
                            <strong>
                                MA <em>'.$price.'</em>
                            </strong>
                        '.$dc.'
                        </div>
                      
                        <div class="buttons">
                            <img src="'.base_url().'Images/ItemshopNew/buy_btn.gif" id="buy-'.$item['ProductNum'].'" class="btnBuy pointer" alt="구매하기">
                            
                            <img src="'.base_url().'Images/ItemshopNew/cart_btn.gif" id="cart-'.$item['ProductNum'].'" class="btnCart pointer" alt="장바구니">
                      
                        </div>
                        
                    </figure>
                </li>';
            } else {

                echo '<li class="">
                    <figure class="'.$figure.'">
                        '.$sticker.'
                        '.$wrapper.'
                        
                        
                        <figcaption>'.$itemname.'</figcaption>
                        <p class="orignal-price">'.$originalprice.'</p>
                        <div class="sale-price">
                            <strong>
                                MA <em>'.$price.'</em>
                            </strong>
                        '.$dc.'
                        </div>
                      
                        <div class="buttons">
                            <img src="'.base_url().'Images/ItemshopNew/buy_btn.gif" id="buy-'.$item['ProductNum'].'" class="btnBuy pointer" alt="구매하기">
                            
                            <img src="'.base_url().'Images/ItemshopNew/cart_btn.gif" id="cart-'.$item['ProductNum'].'" class="btnCart pointer" alt="장바구니">
                            <!-- <img src="'.base_url().'Images/ItemshopNew/wish_btn.gif" id="wish-'.$item['ProductNum'].'" class="btnWish pointer" alt="찜"> -->
                        </div>
                        
                    </figure>
                </li>';
            }
            $i++;
        }  
    } 
    echo '</ul>';
    ?>
		<div class="clear"></div>
           <div style="align-items: center;display: flex;justify-content: center;">
                   <?=(($pages == 1) ? "" :$pages)?>
           </div>
		
		<div class="clear"></div>
	</section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>



<script type="text/javascript">
    $(document).ready(function () {
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.item-wrapper .item-image').click(function () {
            var itemNum = $(this).attr('id');
            Common.OpenCenterWindow(480, 533, '<?=base_url()?>ItemShop/ItemBuy/' + itemNum, 'ItemBuy', false);
        });

        $('.item-wrapper2 .item-image').click(function () {
            var itemNum = $(this).attr('id');
            Common.OpenCenterWindow(480, 700, '<?=base_url()?>ItemShop/ItemBuy/' + itemNum, 'ItemBuy', false);
        });
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        $('.btnBuy').click(function () {
            if (fnLoginChech()) {
                let itemNum = $(this).attr('id').split('-')[1];

                if (confirm('Do you want to buy This item?')) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        contentType: "application/json; charset=utf-8",
                        url: '<?=base_url()?>WebService/ItemBuy',
                        data: {ItemNum : itemNum , ItemCount : 1},
                        async: false,
                        success: function (data) {
                            let result = data.result;

                            if (result == 0) {
                                if (confirm('You have purchased an item. \n \n Do you want to go to your History page?')) {
                                    location.href = '<?=base_url()?>ItemShop/BuyHistory';
                                } else {
                                    location.href = location.href
                                }
                            } else {
                                if (result == 1) {
                                    alert('Not Enough EPoints.');
                                } else if (result == 2) {
                                    alert('Purchase quantity exceeds quantity left.');
                                } else if (result == 3) {
                                    alert('You have exceeded the number available today.');
                                } else if (result == 4) {
                                    alert('You have exceeded the number of purchases per account.');
                                } else if (result == 5) {
                                    alert('The sale period has ended.');
                                } else if (result == 6) {
                                    alert('This item is for sale.');
                                } else if (result == 7) {
                                    alert('We have exceeded the number of available weekly purchases.');
                                } else if (result == 8) {
                                    alert('You have exceeded the number of monthly purchases.');
                                }
                            }
                        },
                        error: function (data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        }
                    });

                }
            }
        });

        $('.btnCart').click(function () {
            if (fnLoginChech()) {
                var itemNum = $(this).attr('id').split('-')[1];
                if (confirm('Would you like to add it to your shopping cart?')) {
                    $.ajax({                 
                        type: 'get',
                        dataType: 'json',
                        contentType: "application/json; charset=utf-8",
                        url: '<?=base_url()?>WebService/ItemCart',
                        data: { ItemNum : itemNum },
                        success: function (data) {
                            var result = data.result;
                            if (result == 0) {
                                alert('Add to cart.');
                            } else {
                                if (result == 1) {
                                    alert('This item is discontinued.');
                                } else if (result == 2) {
                                    alert('This item has already been registered.');
                                } else if (result == -1) {
                                    alert('System error. Please try again.');
                                }
                            }
                        },
                        error: function (data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        }
                    });
                }
            }
        });


        $('#btnSearch').click(function () {
                let ItemName = $('#txtSearchString').val();
                window.location.href = "<?=base_url()?>ItemShop/ItemFind/" +ItemName;
                    $.ajax({                 
                        type: 'get',
                        dataType: 'json',
                        contentType: "application/json; charset=utf-8",
                        url: '<?=base_url()?>ItemShop/ItemFind',
                        data: { ItemNum : itemNum },
                        success: function (data) {
                            alert('here');
                        },
                        error: function (data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        }
                    });
            // $('#frmitemshop').attr('action', '' + base_url + 'ItemShop/ItemFind').submit();
        });

        let input = document.getElementById("txtSearchString");
        input.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("btnSearch").click();
            }
        });
    });
</script>