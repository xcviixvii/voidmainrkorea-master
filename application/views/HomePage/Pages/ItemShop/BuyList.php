<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Buy History</title>
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
                        <section class="buy-history">
                            <table class="board-list">
                                <caption>ItemList List</caption>
                                <colgroup>
                                    <col style="width:110px;">
                                    <col style="width:500px;">
                                    <col style="width:110px;">
                                    <col style="width:110px;">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th scope="col"><img src="<?=base_url()?>Images/Board/no_th.gif" alt="number"></th>
                                    <th scope="col"><img src="<?=base_url()?>Images/Board/item_th.gif" alt="title"></th>
                                    <th scope="col"><img src="<?=base_url()?>Images/Board/cost_th.gif" alt="Date Created"></th>
                                    <th scope="col"><img src="<?=base_url()?>Images/Board/date_th.gif" alt="Date Created"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ctr = 1;
                                    foreach ($BuyList as $row) {
                                        $Prod = explode(",",$row['ItemsList']);
                                        $ItemImg = '';
                                        foreach ($Prod as $Item) {
                                            $data = $this->llllllllz_model->getitemebyitem($Item);
                                            $ItemImg .=  '
    <img src="'.base_url().'Uploadfiles/ItemShop/'.$data[0]['ItemSS'].'" style="width:35; height:35; padding: 2px;
    border: 1px solid rgb(30, 144, 255); margin-top: 2px;" title="'.$data[0]['ItemName'].'">';
                                        }

                                        echo '<tr>
                                        <td style="font-size:10px;font-weight:bold;">'.$row['TransNo'].'</td>
                                        <td>'.$ItemImg.'</td>
                                        <td style="font-size:10px;font-weight:bold;">'.formatnumber3($row['Cost']).'</td>
                                        <td style="font-size:10px;font-weight:bold;">'.formatdate51($row['PurchaseDate']).'</td>
                                        </tr>';

                                        $ctr++;
                                    }
                                    ?>
                                </tbody>
                                </table>
                                <div style="align-items: center;display: flex;justify-content: center;">
                                        <?=$pages?>
                                </div>
                        </section>
                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
  

