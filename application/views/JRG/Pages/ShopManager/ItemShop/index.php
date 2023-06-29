<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?>
</title>

<div class="az-content-body">
      <div class="container">
        <?php
        renderview('JRG/Pages/ShopManager/ItemShop/SideMenu')
        ?>

        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
         
            <div class="d-flex flex-row">
              <div class="table-responsive">
              <div class="az-mail-options">
                <label class="ckbox">
   
                </label>
                <div class="btn-group">
                  <button class="btn btn-light disabled" id="allright"><i class="typcn typcn-trash"></i></button>
                </div><!-- btn-group -->
              </div>
                <table class="table table-bordered table-hover mg-b-10">
                  <thead>
                    <tr>
                      <th><label class="ckbox"><input type="checkbox" id="checkall"><span></span></label></th>
                      <th>IMG</th>
                      <th>Item</th>
                      <th>Price</th>
                      <th>Stock</th>
                      <td>Section</td>
                      <td>Category</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody style="font-size: 11px;" class="item-list">
                   <?php
                   $ctr = 1;
                   foreach ($prod as $row) {
                     echo '<tr>
                      <td><label class="ckbox"><input type="checkbox" id="checkbox-'.$ctr.'" class="cb-element"><span></span></label></td>
                      <td><img id="blah"  style="height:25px; width:25px; border: 1px solid skyblue;"  '.(($row['ItemSS'] == "") ? 'src="'.base_url().'Images/ItemShopNew/Default.jpg"':'src="'.base_url().'Uploadfiles/ItemShop/'.$row['ItemSS'].'"').'></td>
                      <td>'.$row['ItemName'].'</td>
                      <td>'.$row['ItemPrice'].'</td>
                      <td>'.$row['Itemstock'].'</td>
                      <td>'.$row['sectionname'].'</td>
                      <td>'.$row['categoryname'].'</td>
                      <td align="center"><a href="'.base_url().'adminpanel/ItemShop/EditItem/'.$row['ProductNum'].'"><i class="fas fa-edit"></i></a></td>
                     </tr>';
                     $ctr++;
                   }
                   ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>

    
<script type="text/javascript">
    $("#checkall").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $(".cb-element").change(function () {
        if ($(".cb-element").length == $(".cb-element:checked").length)
            $("#checkall").prop('checked', true);
        else
            $("#checkall").prop('checked', false);
    });
</script>