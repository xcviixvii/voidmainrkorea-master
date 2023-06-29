[
<?php
    foreach ($GMPick as $row) {
        ?>
        {
            "ItemNum":"<?=$row['ProductNum']?>",
            "ItemImg":"<?=base_url()?>Uploadfiles/ItemShop/<?=$row['ItemSS']?>",
            "ItemName":"<?=$row['ItemName']?>",
            "ItemPrice":"<?=$row['ItemPrice']?>"
        }
        <?php
        if(next($GMPick)) {
            echo ',';
        }
    }
?>
]
