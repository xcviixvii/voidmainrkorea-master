<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Cart List</title>
<style>
    #subitem {
        width: 48px;margin: 5 5 5 0;padding: 4px;border: 1px solid #e9e9e9;background: #fff;
    }
    .subimg {
        width: 48px; height:48px;
    }
</style>
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
                		<span class="page-history-select">ITEM SHOP</span>
                	</section>
                	<h3 class="sub-title">
                		<img src="<?=base_url()?>Images/SubTitle/Itemshop/itemshop_sub_title.gif" alt="Item Shop">
                	</h3>
	
	               
                   <section class="shopping-cart">
                    <p class="itemCount">
                    <strong class="tahoma12-redB"><?=count($Cart)?></strong> Items Registered
                    </p>

                    <ul>
                        <?php
                        $TotalPrice = 0;
                        $TotalDiscount = 0;
                        $TotalMCoin = 0;
                            foreach ($Cart as $item) {
                                

                                if($item['discount'] != NULL){
                                    $Discount = $item['ItemPrice'] * ($item['discount'] / 100);
                                    $Price =  $item['ItemPrice'];
                                    $DisPrice = $item['ItemPrice'] - $Discount;

                                } else {
                                    $Discount = 0;
                                    $Price = $item['ItemPrice'];
                                    $DisPrice = 0;
                                }
                                
                                $MCoin = $Price * 0.10;
                                
                                ?>
                                <li>
                                    <div class="item">
                                        <div class="buy-check">
                                            <input type="checkbox" id="chkBuy<?=$item['ProductNum']?>" name="chkBuy" value="<?=$item['ProductNum']?>" title="<?=$Price?>:<?=$DisPrice?>:<?=$MCoin?>">
                                        </div>
                                        <div class="item-img">
                                            <div class="item-wrapper">
                                                <img src="<?=base_url()?>Uploadfiles/ItemShop/<?=$item['ItemSS']?>">
                                            </div>
                                            <?php
                                                if($item['SubItem']){
                                                    ?>
                                                <div class="item-wrapper3" id="subitem">
                                                    <img src="<?=base_url()?>Uploadfiles/ItemShop/<?=$this->llllllllz_model->get_productbyimg($item['SubItem'])?>"
                                                        style="width: 48px; height:48px;">
                                                </div> 
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="item-info">
                                            <header>
                                                <h4 id="itemName<?=$item['ProductNum']?>"><?=$item['ItemName']?></h4>
                                                <p><?=$item['sectionname']?> &gt; <?=$item['categoryname']?></p>
                                                <div style="float:right; margin-top:-40px;"><img src="<?=base_url()?>Images/Button/delete_btn.gif" id="<?=$item['ProductNum']?>" class="pointer btnDelete" alt="Delete"></div>
                                            </header>
                                            
                                            <div class="price">
                                            <div class="left">
                                            <span class="rcoinPrice">
                                            <strong>
                                                <?php
                                                if($item['discount'] != NULL){
                                                    echo '<p class="orignal-price" style="text-decoration: line-through;">'.formatnumber3($Price).'</p><div >
                                                        <strong>
                                                            '.($Price - $Discount).'
                                                        </strong>
                                                   (<em style="font-family: Tahoma;">'.$item['discount'].'%</em>)
                                                    </div>';
                                                } else {
                                                    echo formatnumber3($Price);
                                                }
                                                ?>
                                                
                                            </strong>
                                            </span>
                                            </div>
                                            <div class="right">
                                            </div>
                                            <div class="clear"></div>
                                            </div>
                                            <div class="item-cost">
                                                <table>
                                                    <tr>
                                                        <th><b style="text-transform: uppercase;">MILEAGE</b></th>
                                                        <td><?=formatnumber3($Price * 0.10)?></td>
                                                       
                                                    </tr>
                                                    <?php
                                                        if($item['ItemPeriod'] != NULL){
                                                            echo '<tr>
                                                                <th><b style="text-transform: uppercase;">Sale Period</b></th>
                                                                <td>'.formatdate4($item['ItemPeriod']).' - '.formattime1($item['ItemPeriod']).'</td> 
                                                            </tr>';
                                                        }
                                                    ?>
                                                    

                                                    <tr>
                                                        <th><b style="text-transform: uppercase;">Qty</b></th>
                                                        <td>
                                                            <input type="text" id="txt-num<?=$item['ProductNum']?>" class="txt-num" value="1" readonly="readonly">
                                                            <span class="num-button">
                                                            <a id="btnUP<?=$item['ProductNum']?>" class="num-up" title="<?=$Price?>:<?=$DisPrice?>:<?=$MCoin?>">+1</a>
                                                            <a id="btnDown<?=$item['ProductNum']?>" class="num-down" title="<?=$Price?>:<?=$DisPrice?>:<?=$MCoin?>">-1</a>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </li>
                                <?php
                                $TotalDiscount = $TotalDiscount + $Discount;
                                $TotalPrice = $TotalPrice + $Price;
                                $TotalMCoin = $TotalMCoin + $MCoin;
                            }
                        ?>
                    </ul>

                    



                    
                    <div class="textLeft">
                    <img src="<?=base_url()?>Images/Button/selectall_btn.gif" id="btnAllCheck" style="display:none;" class="pointer all" alt="select all">
                    <img src="<?=base_url()?>Images/Button/delete_selected_btn.gif" id="btnCheckDelete" class="pointer" alt="delete selected">
                    </div>
                   

                    <div class="price-info">
                        <header>Total Amount</header>
                    <div class="total-cost-wrapper">
                        <div class="total-cost">
                        <span>
                        Qty. <strong id="selectCount">0</strong>
                        </span>
                        <span>
                        MILEAGE <strong id="addRPoint">0</strong> <!-- <?=formatnumber3($TotalMCoin)?> -->
                        </span>
                        <span>
                        price <strong id="originalSellPrice">0</strong> <!-- <?=formatnumber3($TotalPrice)?> -->
                        </span>
                        <span>
                        Sale Price <strong id="totalDiscount">0</strong> <!-- <?=formatnumber3($TotalPrice - $TotalDiscount)?> -->
                        </span>
                        </div>


                    </div>
         
                    <div class="payment">
                            <table>
                                <tr>
                                    <th>E-Points</th>
                                    <td><?=formatnumber3(((!$points) ? "0":"".$points[0]['EPoint'].""))?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td id="totalPrice">0</td>
                                </tr>
                                <tr class="cash-error" id="coin2">
                                    <th>Insufficient Balance</th>
                                    <td><span id="error-coin">0</span></td>
                                </tr>
                            </table>
                        </div>
                    <div class="clear"></div>
                    </div>


                    <div class="textCenter pt10">
                    <img src="<?=base_url()?>Images/Button/buy_selected_btn.gif" id="btnBuy" class="pointer" alt="buy">

                    <!-- <img src="<?=base_url()?>Images/Button/buy_selected_btn.gif" id="btnAllCheck" class="pointer" alt="buy all">
                    </div> -->
                   </section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
  

<script type="text/javascript">
        var userCoin = '<?=((!$points) ? "0":"".$points[0]['EPoint']."")?>';
        //$('#coin2').hide();
        $(document).ready(function() {
            // Common.WebAdminRecord("ItemShop");
            // $("#menu-itemshop-recharge").show();
            // $("#menu-itemshop").show();

            // $('#itemshop5').attr("src", $('#itemshop5').attr('src').replace("off", "on"));
            // $('#itemshop5').removeClass("subMenuTitle");
            
            
            $('#btnAllCheck').click(function() {
                if ($(this).hasClass('all')) {
                    $('input:checkbox[name=chkBuy]:not(checked)').attr('checked', 'checked');
                    $(this).removeClass('all');
                } else {
                    $('input:checkbox[name=chkBuy]:checked').attr('checked', '');
                    $(this).addClass('all');
                }

                $('input:checkbox[name=chkBuy]').each(function() {
                    var checked = $(this).attr('checked');

                    var id = $(this).attr('id').replace('chkBuy', '');

                    var original = $(this).attr('title').split(':')[0];
                    var discount = $(this).attr('title').split(':')[1];
                    var rpont = $(this).attr('title').split(':')[2];
                    var count = $('#txt-num' + id).val();

                    var selectCount = $('#selectCount').text();
                    if (checked) {
                        selectCount++;
                        $('#selectCount').text(selectCount);
                        

                        fnItemPlus(original, discount, rpont, count);
                    } else {
                        selectCount--;
                        $('#selectCount').text(selectCount);
                        $(this).attr('checked', '');

                        fnItemMinus(original, discount, rpont, count);
                    }
                });
            });

            $('input:checkbox[name=chkBuy]').click(function() {
                var checked = $(this).is(':checked');
                
                var id = $(this).attr('id').replace('chkBuy', '');
                var original = $(this).attr('title').split(':')[0];
                var discount = $(this).attr('title').split(':')[1];
                var rpont = $(this).attr('title').split(':')[2];
                var count = $('#txt-num' + id).val();

                var selectCount = $('#selectCount').text();
               
                if (checked) {
                    selectCount++;
                    $('#selectCount').text(selectCount);

                    fnItemPlus(original, discount, rpont, count);
                } else {
                    selectCount--;
                    $('#selectCount').text(selectCount);
                    // $(this).attr('checked', '');

                    fnItemMinus(original, discount, rpont, count);
                }
            });

            // Number of items UP
            $('.num-up').click(function() {
                var id = $(this).attr('id').replace('btnUP', '');
                var original = $(this).attr('title').split(':')[0];
                var discount = $(this).attr('title').split(':')[1];
                var rpoint = $(this).attr('title').split(':')[2];

                if (!$('#chkBuy' + id).is(':checked')) {
                    alert('No item has been selected.');
                    return false;
                }

                var prevVal = $('#txt-num' + id).val();

                // transfer amounts
                var prevOriginal = original * prevVal;
                var prevDiscount = discount * prevVal;
                var prevRPoint = (rpoint * prevVal).toFixed(2);

                prevVal++;

                $('#txt-num' + id).val(prevVal);

                var nowVal = prevVal;

                // current amounts
                var nowOriginal = original * nowVal;
                var nowDiscount = discount * nowVal;
                var nowRPoint = (rpoint * nowVal).toFixed(2);

                // present-previous
                var nextOriginal = nowOriginal - prevOriginal;
                var nextDiscount = nowDiscount - prevDiscount;
                var nextRPoint = nowRPoint - prevRPoint;

                // input
                var addRPoint = Number($('#addRPoint').text().replace(',', ''));
                var originalSellPrice = Number($('#originalSellPrice').text().replace(',', ''));
                var totalDiscount = Number($('#totalDiscount').text().replace(',', ''));

                addRPoint += nextRPoint;
                originalSellPrice += nextOriginal;

                if (discount != 0) {
                    totalDiscount += nextOriginal - nextDiscount;
                }

                $('#addRPoint').text(Common.Comma(addRPoint.toFixed(2)));
                $('#originalSellPrice').text(Common.Comma(originalSellPrice));
                $('#totalDiscount').text(Common.Comma(totalDiscount));

                var totalPrice = Number($('#totalPrice').text().replace(',', ''));
                if (discount != 0) {
                    totalPrice += (nextOriginal - (nextOriginal - nextDiscount));
                } else {
                    totalPrice += nextOriginal;
                }
                $('#totalPrice').text(Common.Comma(totalPrice));

                if (totalPrice > userCoin) {
                    $('#coin1').hide();
                    $('#coin2').show();
                    $('#error-coin').text(Common.Comma(totalPrice - userCoin));
                    $('#btnBuy').hide();
                } else {
                    $('#coin1').show();
                    $('#coin2').hide();
                    $('#buy-coin').text(Common.Comma(userCoin - totalPrice));
                    $('#error-coin').text('0');
                    $('#btnBuy').show();
                }
            });

            // Number of items DOWN
            $('.num-down').click(function() {
                var id = $(this).attr('id').replace('btnDown', '');
                var original = $(this).attr('title').split(':')[0];
                var discount = $(this).attr('title').split(':')[1];
                var rpoint = $(this).attr('title').split(':')[2];

                if (!$('#chkBuy' + id).is(':checked')) {
                    alert('No item has been selected.');
                    return false;
                }

                var prevVal = $('#txt-num' + id).val();

                // Transfer amounts
                var prevOriginal = original * prevVal;
                var prevDiscount = discount * prevVal;
                var prevRPoint = (rpoint * prevVal).toFixed(2);

                prevVal--;

                if (prevVal < 0 || prevVal == 0) {
                    prevVal = 1;
                }

                $('#txt-num' + id).val(prevVal);

                var nowVal = prevVal;

                // Current amounts
                var nowOriginal = original * nowVal;
                var nowDiscount = discount * nowVal;
                var nowRPoint = (rpoint * nowVal).toFixed(2);

                // Previous-Current
                var nextOriginal = prevOriginal - nowOriginal;
                var nextDiscount = prevDiscount - nowDiscount;
                var nextRPoint = prevRPoint - nowRPoint;

                // input
                var addRPoint = Number($('#addRPoint').text().replace(',', ''));
                var originalSellPrice = Number($('#originalSellPrice').text().replace(',', ''));
                var totalDiscount = Number($('#totalDiscount').text().replace(',', ''));

                addRPoint -= nextRPoint;
                originalSellPrice -= nextOriginal;

                if (discount != 0) {
                    totalDiscount -= nextOriginal - nextDiscount;
                }

                $('#addRPoint').text(Common.Comma(addRPoint.toFixed(2)));
                $('#originalSellPrice').text(Common.Comma(originalSellPrice));
                $('#totalDiscount').text(Common.Comma(totalDiscount));

                var totalPrice = Number($('#totalPrice').text().replace(',', ''));
                if (discount != 0) {
                    totalPrice -= (nextOriginal - (nextOriginal - nextDiscount));
                } else {
                    totalPrice -= nextOriginal;
                }
                $('#totalPrice').text(Common.Comma(totalPrice));

                if (totalPrice > userCoin) {
                    $('#coin1').hide();
                    $('#coin2').show();
                    $('#error-coin').text(Common.Comma(totalPrice - userCoin));
                    $('#btnBuy').hide();
                } else {
                    $('#coin1').show();
                    $('#coin2').hide();
                    $('#buy-coin').text(Common.Comma(userCoin - totalPrice));
                    $('#error-coin').text('0');
                    $('#btnBuy').show();
                }
            });

            /*
            * When a product is selected
            * original: Item amount
            * discount: Item discount amount
            * rpoint: R point
            * count: number of products
            */
            function fnItemPlus(original, discount, rpoint, count) {
                var addRPoint = Number($('#addRPoint').text().replace(',', ''));
                var originalSellPrice = Number($('#originalSellPrice').text().replace(',', ''));
                var totalDiscount = Number($('#totalDiscount').text().replace(',', ''));

                addRPoint += rpoint * count;
                originalSellPrice += original * count;

                if (discount != 0) {
                    totalDiscount += (original - discount) * count;
                }

                $('#addRPoint').text(Common.Comma(addRPoint.toFixed(2)));
                $('#originalSellPrice').text(Common.Comma(originalSellPrice));
                $('#totalDiscount').text(Common.Comma(totalDiscount));

                var totalPrice = Number($('#totalPrice').text().replace(',', ''));
                totalPrice = originalSellPrice - totalDiscount;
                $('#totalPrice').text(Common.Comma(totalPrice));

                if (totalPrice > userCoin) {
                    $('#coin1').hide();
                    $('#coin2').show();
                    $('#error-coin').text(Common.Comma(totalPrice - userCoin));
                    $('#btnBuy').hide();
                    $('#btncoin').show();
                } else {
                    $('#coin1').show();
                    $('#coin2').hide();
                    $('#buy-coin').text(Common.Comma(userCoin - totalPrice));
                    $('#error-coin').text('0');
                    $('#btnBuy').show();
                    $('#btncoin').hide();
                }
            }

            /*
            * When selecting a product
            * original: Item amount
            * discount: Item discount amount
            * rpoint: R point
            * count: number of products
            */
            function fnItemMinus(original, discount, rpoint, count) {
                var addRPoint = Number($('#addRPoint').text().replace(',', ''));
                var originalSellPrice = Number($('#originalSellPrice').text().replace(',', ''));
                var totalDiscount = Number($('#totalDiscount').text().replace(',', ''));

                addRPoint -= rpoint * count;
                originalSellPrice -= original * count;

                if (discount != 0) {
                    totalDiscount -= (original - discount) * count;
                }

                $('#addRPoint').text(Common.Comma(addRPoint.toFixed(2)));
                $('#originalSellPrice').text(Common.Comma(originalSellPrice));
                $('#totalDiscount').text(Common.Comma(totalDiscount));

                var totalPrice = Number($('#totalPrice').text().replace(',', ''));
                totalPrice = originalSellPrice - totalDiscount;
                $('#totalPrice').text(Common.Comma(totalPrice));

                if (totalPrice > userCoin) {
                    $('#coin1').hide();
                    $('#coin2').show();
                    $('#error-coin').text(Common.Comma(totalPrice - userCoin));
                    $('#btnBuy').hide();
                    $('#btncoin').show();
                } else {
                    $('#coin1').show();
                    $('#coin2').hide();
                    $('#buy-coin').text(Common.Comma(userCoin - totalPrice));
                    $('#error-coin').text('0');
                    $('#btnBuy').show();
                    $('#btncoin').hide();
                }
            }

            $('.btnDelete').click(function() {
                let ItemNums = $(this).attr('id');
                if (confirm('Delete?')) {
                    $.ajax({   
                    type: 'get',
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8",
                    url: '<?=base_url()?>ItemShop/ItemCartDeleteAll',
                    data : { ItemNums: ItemNums },
                        success: function (data) {
                            if (data[0].msg == 0) {
                                alert('Deleted');
                                location.href = location.href;
                            } else {
                                alert('Deletion failed. Please try again.');
                                location.href = location.href;
                            }
                        },
                        error: function (data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        } 
                    });
                }
            });

            $('#btnCheckDelete').click(function() {
                if ($('input:checkbox[name="chkBuy"]:checked').length == 0) {
                    alert('No item has been selected.');
                    return false;
                }

                var itemNums = ''
                $('input:checkbox[name="chkBuy"]:checked').each(function() {
                    var id = $(this).attr('id').replace('chkBuy', '');
                    var itemNum = $(this).val();

                    itemNums = itemNums + itemNum + ',';

                });

                itemNums = itemNums.substring(0, itemNums.length - 1);

                if (confirm('Delete?')) {
                    $.ajax({   
                    type: 'get',
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8",
                    url: '<?=base_url()?>ItemShop/ItemCartDeleteAll',
                    data : { ItemNums: itemNums },
                        success: function (data) {
                            if (data[0].msg == 0) {
                                alert('Deleted');
                                location.href = location.href;
                            } else {
                                alert('Deletion failed. Please try again.');
                                location.href = location.href;
                            }
                        },
                        error: function (data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        }  
                    });
                }
            });

            $('#btnBuy').click(function() {
                if ($('input:checkbox[name="chkBuy"]:checked').length == 0) {
                    alert('No item has been selected.');
                    return false;
                }

                var itemNums = '';
                var itemCounts = '';
                var itemNames = '';

                $('input:checkbox[name="chkBuy"]:checked').each(function() {
                    var id = $(this).attr('id').replace('chkBuy', '');
                    var itemNum = $(this).val();
                    var itemCount = $('#txt-num' + id).val();
                    var itemName = $('#itemName' + id).text();

                    itemNums = itemNums + itemNum + ',';
                    itemCounts = itemCounts + itemCount + ',';
                    itemNames = itemNames + itemName + ',';
                });

                itemNums = itemNums.substring(0, itemNums.length - 1);
                itemCounts = itemCounts.substring(0, itemCounts.length - 1);
                itemNames = itemNames.substring(0, itemNames.length - 1);

                if (confirm('Buy with E-Points?')) {
                    
                    let isValid = true;
                    $.ajax({   
                    type: 'get',
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8",
                    url : '<?=base_url()?>ItemShop/ItemCartBuy',
                    data : { ItemNums: itemNums, ItemCounts: itemCounts, ItemNames: itemNames },
                    success: function (data) {
                            let result = data;
                            let StockChecker = data[0].OutStock;
                            // let RItemName = result.ItemName;

                            if (StockChecker != 0) {
                                const array = result;
                                array.forEach(function (item, index) {
                                    let retVal = item.retVal;
                                    item.ItemName;
                                    if (item.retVal != 0) {
                                        itemName = item.ItemName;
                                    }

                                    if (item.retVal != 0) {
                                        isValid = false;
                                        fnItemCartChechMessage(itemName, retVal);
                                    }

                                });
                            } else {
                                alert('Item has been purchased.');
                                location.href = location.href;
                            }
                        },
                        error: function (data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        }  
                });
            }
            });
        

            function fnItemCartChechMessage(itemName, result) {
                if (result == 1) {
                    alert('You need more E-Points.');
                } else if (result == 2) {
                    alert(itemName + ' is out of stock or entered exceeding amount');
                } else if (result == 3) {
                    alert(itemName + ' cannot be purchased more today.');
                } else if (result == 4) {
                    alert(itemName + 'cannot be purchased due to purchase limit per account.');
                } else if (result == 5) {
                    alert(itemName + ' Sale period has ended!');
                } else if (result == 6) {
                    alert(itemName + ' No longer on sale.');
                }

                return false;
            }

            $('#btnAllCheck').click();
        });
    </script>
</script>
