<?php
if($product[0]['ItemSS'] == ""){
    $source = base_url().'Images/ItemShopNew/Default.jpg';
} else {
    $source = base_url().'Uploadfiles/ItemShop/'.$product[0]['ItemSS'];
}

$string = strip_tags($product[0]['ItemName']);
$itemName = substr($string,0,30).''.((strlen($product[0]['ItemName']) >30) ? "...":"").'';

$prod1 = $this->llllllllz_model->getshopcfg($product[0]['ProductNum']);

if($prod1){
    if($prod1[0]['discount'] != NULL){
        $originalprice = $product[0]['ItemPrice'];
        $discount = $product[0]['ItemPrice'] * ($prod1[0]['discount'] / 100);
        $dc = '<span>(<em>'.$prod1[0]['discount'].'%</em>)</span>';
        $price =  $product[0]['ItemPrice'] - $discount;

        $discounted = '
                        <span class="orignal-price">'.$originalprice.'</span>
                        <strong class="discount">
                            '.$price.'<em style="font-family: Tahoma;">'.$prod1[0]['discount'].'% Sale</em>
                        </strong>';

        $mileearn = ($price * 0.01);
    } else {
        $originalprice = "";
        $dc = "";
        $price = $product[0]['ItemPrice'];
        $discounted = '<strong>'.$product[0]['ItemPrice'].'</strong>';
        $mileearn = ($price * 0.01);
    }


    if($prod1[0]['ItemSub'] != NULL){
        $itemN = $this->llllllllz_model->getitemebyitem($prod1[0]['ItemSub']);
        if($itemN[0]['ItemSS'] == ""){
            $sourcesub = base_url().'Images/ItemShopNew/Default.jpg';
        } else {
            $sourcesub = base_url().'Uploadfiles/ItemShop/'.$itemN[0]['ItemSS'];
        }
        $item2 = '<div class="item2">
                        <div class="item_titie_event"><img src="'.base_url().'Images/ItemshopNew/event_sticker.png" alt="event"></div>
                        <div class="item-title">
                            <h2>'.$itemN[0]['ItemName'].'</h2>
                            <p>'.$itemN[0]['sectionname'].' &gt; '.$itemN[0]['categoryname'].'</p>
                        </div>
                        <div class="item-img">
                            <div class="item-wrapper">
                                <img src="'.$sourcesub.'" id="Img2" alt="'.$itemN[0]['ItemName'].'">
                            </div>
                        </div>
                        <div class="item-info2">
                             '.$itemN[0]['ItemComment'].'
                        </div>
                        <div class="clear"></div>
                    </div>';
    } else {
        $item2 = "";
    }
} else {
    $originalprice = "";
    $dc = "";
    $price = $product[0]['ItemPrice'];
    $discounted = '<strong>'.$product[0]['ItemPrice'].'</strong>';
    $item2 = "";
    $mileearn = ($price * 0.01);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ran Online</title>
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Popup.css" media="all">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/ItemShop.css" media="all">
    <script type="text/javascript" src="<?=base_url()?>Scripts/Common.js"></script>
  
</head>
<body>
    <section class="item-buy">
        <div class="wrapper">
            <h1><img src="<?=base_url()?>Images/ItemshopNew/itembuy_title.gif" alt="Purchase Item"></h1>
            <div class="container">
                <header class="item">
                    <div class="item-img">
                        <div class="item-wrapper">
                            <img src="<?=$source?>" id="<?=$product[0]['ProductNum']?>" class="item-image pointer" alt="<?=$itemName?>">
                        </div>
                        <div>
                        
                                <a href="#" onclick="alert('No preview images.');"><img src="<?=base_url()?>Images/ItemshopNew/item_preview_btn.gif" alt="Item Preview"></a>
                          
                        </div>
                    </div>
                    <section class="item-title">
                        <h2><?=$itemName?></h2>
                        <p><?=$product[0]['sectionname']?> &gt; <?=$product[0]['categoryname']?></p>
                    </section>
                    <article id="ItemInfo" class="item-info">
                    <?=$product[0]['ItemComment']?>
                    </article>
                    <div class="clear"></div>
                </header>
                <?=$item2?>


                 
                <table class="item-cost">
                <caption>Item Price</caption>
                <tbody>
                <tr>
                    <th>Class</th>
                    <td>
                        <strong>
                    <?=ItemDisc($product[0]['ItemDisc'])?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>
                    
                        <!-- PRICE AND DISCOUNT HERE -->
                        <?=$discounted?>
                    </td>
                </tr>
                <tr>
                    <th>Earn Miles</th>
                    <td>
                    
                        <strong><?=formatnumber($mileearn)?></strong>
                    
                    </td>
                </tr>
                <?php
                if($prod1){
                    if($prod1[0]['ItemPeriod'] != NULL){
                        echo '<tr>
                                <th>Period of sale</th>
                                <td>
                                    Sales up to <strong>'.$prod1[0]['ItemPeriod'].'</strong> 
                                </td>
                            </tr>';
                    }
                }
                ?>
                
                <?php
                if($this->session->userdata('IsAuth') == 'True'){
                    echo "<tr>
                            <th>Stock's</th>
                            <td>
                            <strong>".$product[0]['Itemstock']."</strong>
                            </td>
                        </tr>";
                } else {
                    echo "<tr>
                            <th>Stock's</th>
                            <td>
                            You can only buy if you do <em style='font-family: Tahoma;'>Login</em>
                            </td>
                        </tr>";
                }
                ?>
                


                </tbody>
                </table>
                <footer>
                    <img src="<?=base_url()?>Images/ItemshopNew/recharge_btn.gif" id="btnCash" class="pointer display-none" alt="캐시충전">
                    <img src="<?=base_url()?>Images/ItemshopNew/buy_btn.gif" id="buy-<?=$product[0]['ProductNum']?>" class="pointer display-none btnBuy" alt="Purchase">
                    <img src="<?=base_url()?>Images/ItemshopNew/close_btn.gif" id="btnClose" class="pointer display-none" alt="닫기">
                </footer>
            </div>
        </div>
    </section>
    
    <script type="text/javascript" src="<?=base_url()?>Js/Common.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/jquery-1.5.1.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Selector.js"></script>
    
  
    <script type="text/javascript">
        var IsAuth = '<?=(($this->session->userdata('IsAuth')) ? ''.$this->session->userdata('IsAuth').'':"False")?>';
	    var UserID = '<?=(($this->session->userdata('UserID')) ? ''.$this->session->userdata('UserID').'':"")?>';
	    var base_url = '<?=base_url()?>';
        
        var viewPrice = '<?=$product[0]['ItemPrice']?>';
        var userCash = '<?=((!$points) ? "0":"".$points[0]['EPoint']."")?>';

        $(document).ready(function() {
            //$('#total-payment').text('5310');

            // 버튼은 보여주지 않고 시작한다.
            if (IsAuth == 'False') {
                $('#btnCash').hide();
                $('.btnBuy').hide();
                $('#btnClose').show();
            } else {
                if (Number(userCash) >= Number(viewPrice)) {
                    $('#btnCash').hide();
                } else {
                    $('#btnCash').show();
                }

                if (Number(userCash) < Number(viewPrice)) {
                    $('.btnBuy').hide();
                } else {
                    $('.btnBuy').show();
                }

                $('#btnClose').show();
            }

            $('#btnUP').click(function() {
                var val = $('#txt-num').val();

                val++;

                $('#txt-num').val(val);

                if (IsAuth == 'True') {
                    var totalPrice = viewPrice * val;
                    $('#total-payment').text(Common.Comma(totalPrice));

                    $('#buy-cash').text(Common.Comma(userCash - totalPrice));

                    if (totalPrice > userCash) {
                        $('#user-cash1').hide();
                        $('#user-cash2').show();

                        $('#error-cash').text(Common.Comma(totalPrice - userCash));

                        $('.btnBuy').hide();
                        $('#btnCash').show();
                    } else {
                        $('#user-cash1').show();
                        $('#user-cash2').hide();
                    }
                }
            });

            // 아이템 갯수 DOWN
            $('#btnDown').click(function() {
                var val = $('#txt-num').val();

                val--;

                if (val < 0 || val == 0) {
                    val = 1;
                }

                $('#txt-num').val(val);

                if (IsAuth == 'True') {
                    var totalPrice = viewPrice * val;
                    $('#total-payment').text(Common.Comma(totalPrice));

                    $('#buy-cash').text(Common.Comma(userCash - totalPrice));

                    if (totalPrice > userCash) {
                        $('#user-cash1').hide();
                        $('#user-cash2').show();
                        $('#error-cash').text(Common.Comma(totalPrice - userCash));

                        $('.btnBuy').hide();
                        $('#btnCash').show();
                    } else {
                        $('#user-cash1').show();
                        $('#user-cash2').hide();

                        $('.btnBuy').show();
                        $('#btnCash').hide();
                    }
                }
            });

            $('.btnBuy').click(function() {
                let itemNum = $(this).attr('id').split('-')[1];
                if (confirm('Do you want to buy?')) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        contentType: "application/json; charset=utf-8",
                        url: '<?=base_url()?>WebService/ItemBuy',
                        data: {ItemNum : itemNum , ItemCount : 1},
                        async: false,
                        success: function(data) {
                            let result = data.result;
                            
                            if (result == 0) {
                                    location.href = location.href
                            } else {
                                if (result == 1) {
                                    alert('Cash is Low.');
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
                        error: function(data) {
                            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                        }
                    });
                }
            });

            $('#btnCash').click(function() {
                Common.OpenCenterWindow(500, 412, '<?=base_url()?>ItemShop/PaymentStep1', 'Payment', false);
                self.close();
            });

            $('#btnClose').click(function() {
                self.close();
            });
        });
    </script>
</body>
</html>
