[
<?php
    foreach ($NewItem as $row) {
        ?>
        {
            "ItemNum":"<?=$row['ProductNum']?>",
            "ItemImg":"<?=base_url()?>Uploadfiles/ItemShop/<?=$row['ItemSS']?>",
            "ItemName":"<?=$row['ItemName']?>",
            "ItemPrice":"<?=$row['ItemPrice']?>"
        }
        <?php
        if(next($NewItem)) {
            echo ',';
        }
    }
?>
]
