<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>
<?php
if(count($Caps) > 0){
  $CapsuleStatus = $Caps[0]['CapsuleStatus'];
  $CapsuleReq = $Caps[0]['CapsuleReq'];
} else {
  $CapsuleStatus = 0;
  $CapsuleReq = 0;
}
?>
<div class="az-content-body">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
    <?php

    ?>
        <div class="row">
            <div class="col-md-12">
            
            <div class="az-content-label mg-b-5" style="color:orange;">Note:</div>
            <small>If you Insert a Unique Item you should add SubItem.</small>
            <br />
            <br />
            </div>


            <div class="col-md-4">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                Unique Item
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0">
                  <form method="POST" action="<?=base_url()?>adminpanel/AddCapsuleUniqueItem" data-parsley-validate>
                  <div class="row row-xs">
                    <div class="col-md-9">

                    <div id="slWrapper" class="parsley-select">
                        <select name="UniqueItem" class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                        <option value="" selected=""></option>
                        <?php
                        foreach ($prod as $row) {
                          echo '<option value="'.$row['ProductNum'].'">'.$row['ItemName'].'</option>';
                        }
                        ?>
                        </select>
                        <div id="slErrorContainer"></div>
                      </div>

                    </div><!-- col -->
                     <div class="col-md-3">
                      <button type="submit" class="btn btn-az-primary btn-sm btn-block"><i class="fas fa-plus"></i></button>
                    </div><!-- col -->
                    <hr class="mg-y-30">
                  </div>
                  </form>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->

           <div class="col-md-6">
            <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                Sub Item
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0 ">
                  <form method="POST" action="<?=base_url()?>adminpanel/AddCapsuleSubItem" data-parsley-validate>
                  <div class="row row-xs">
                    <div class="col-md-5">
                      <div id="slWrapper" class="parsley-select">
                        <select name="UniqueItem" class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                        <option value="" selected=""></option>
                        <?php
                        foreach ($Unique as $row) {
                          echo '<option value="'.$row['ItemNum'].'">'.$this->Internal_model->GetShopItemName($row['ItemNum']).'</option>';
                        }
                        ?>
                        </select>
                        <div id="slErrorContainer"></div>
                      </div>
                      
                    </div><!-- col -->
                    <div class="col-md-5">
                      <div id="slWrapper1" class="parsley-select">
                        <select name="SubItem" class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper1" data-parsley-errors-container="#slErrorContainer1" required>
                        <option value="" selected=""></option>
                        <?php
                        foreach ($prod as $row) {
                          echo '<option value="'.$row['ProductNum'].'">'.$row['ItemName'].'</option>';
                        }
                        ?>
                        </select>
                        <div id="slErrorContainer1"></div>
                      </div>
                    </div><!-- col -->
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-az-primary btn-sm btn-block"><i class="fas fa-plus"></i></button>
                    </div><!-- col -->
                    <hr class="mg-y-30">
                  </div>
                  </form>
                </div><!-- card-body -->
              </div><!-- card -->
           </div>

            

            <div class="col-md-12">
            <br />
            </div>

            <div class="col-md-4">
            <div class="table-responsive">
                <div class="az-content-label mg-b-5">Unique Item</div>
                <small>Click The Selected Item</small>
             
                
                    <table class="table table-bordered table-hover mg-b-10" style="user-select: none;font-size:12px;">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($Unique as $row) {
                        echo '<tr style="cursor:pointer;" onclick="GetSubItem('.$row['ItemNum'].')" id="selected'.$row['ItemNum'].'">
                        <td><a href="'.base_url().'adminpanel/CapsuleRemoveItem/'.$row['ItemNum'].'" style="color:red;"><i class="fas fa-minus-square"></i></a>
                        '.$this->Internal_model->GetShopItemName($row['ItemNum']).'
                        <img style="float:right;" src="'.base_url().'Uploadfiles/ItemShop/'.$this->Internal_model->GetShopItemSS($row['ItemNum']).'" width="25" height="25"" />
                        </td>
                        
                        </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            </div>

            <div class="col-md-4">
            <div class="az-content-label mg-b-5">Sub Item</div>
            <small>Click the Minus Button to Remove the item</small>
            <div class="table-responsive">
                <div id="replace">
                <table class="table table-bordered table-hover mg-b-10">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
            </div>

            <div class="col-md-4">
              <div class="az-content-label mg-b-5">Capsule Settings</div>
              <small>Configuration</small>
              <div class="table-responsive">
                <div id="replace">
                  <form method="POST">
                  <table class="table table-bordered table-hover mg-b-10">
                    <thead>
                      <tr>
                        <th>Settings</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="vertical-align:middle;">Capsule</td>
                        <td>
                          <div class="az-toggle <?=(($CapsuleStatus == 1) ? "on":"")?>"><span></span></div> <!-- INSERT ON -->
                            <input type="hidden" id="CapsuleStatus" name="CapsuleStatus" value="<?=(($CapsuleStatus == 1) ? 1:0)?>">
                        </td>
                      </tr>

                      <tr>
                        <td style="vertical-align:middle;">Level Req.</td>
                        <td><input type="text" name="CapsuleReq" value="<?=$CapsuleReq?>" class="form-control form-control-sm"></td>
                      </tr>

                      <tr>
                        <td style="vertical-align:middle;">Capsule Data</td>
                        <td><a href="#">Truncate <i class="fa fa-trash" aria-hidden="true" style="color:red;"></i></a></td>
                      </tr>
                    </tbody>
                    <tfooter>
                      <tr>
                        <td colspan="2">
                          <button type="submit" class="btn btn-primary btn-block btn-sm wd-80" style="float:right;">Save</button>
                        </td>
                      </tr>
                    </tfooter>
                  </table>
                  </form>
                </div>
              </div>
            </div>

          
            
        </div>
             
              

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 


<script>
function GetSubItem(e){

    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/CapsuleSubItem/' + e,
      data: {e: e},
        success:function(data){
      $('#replace').html(data);
      }
    });
}
</script>


  <script>
    $('.az-toggle').on('click', function () {
        $(this).toggleClass('on');
        if ($(this).hasClass('on')) {
            $("#CapsuleStatus").val('1');
        } else {
            $("#CapsuleStatus").val('0');
        }
        
    })
    
    </script>