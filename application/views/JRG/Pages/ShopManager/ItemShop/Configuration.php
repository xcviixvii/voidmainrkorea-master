<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?>
</title>
<style type="text/css">
.badge-discounted {
    color: #fff;
    background-color: #f58c25;
}

.badge-event {
    color: #fff;
    background-color: #9d1bb5;
}
em
{
  padding-left:12px;
  font-family:"Verdana", "굴림";
  font-size:11px;
  color:#444;
  background:url(<?=base_url()?>Images/Icon/discount_icon.gif) no-repeat 0 3px;
}

.price {
    font-family:"Verdana", "굴림";
    font-size: 11px;
    font-weight: bold;
    color: #cc0000;
}
</style>
<?php
$prod12 = $this->llllllllz_model->get_allproduct();

foreach ($prod as $row) {

$config = $this->llllllllz_model->getconfigbyid($row['ProductNum']);

if($config){

  ?>
    <div id="modal<?=$row['ProductNum']?>" class="modal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title"><?=$row['ItemName']?></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="POST" action="<?=base_url()?>adminpanel/updateconfig/<?=$row['ProductNum']?>">
          <div class="modal-body">
            <div class="row row-xs align-items-center mg-b-20">
                <div class="col-md-4">
                  <label class="form-label mg-b-0">Select Sticker</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control select2" name="Sticker" id="sticker<?=$row['ProductNum']?>" style="width: 100%;">
                    <option value=""></option>
                    <option value="1" <?=(($config[0]['ribbon'] == 1) ? "selected":"")?>>Limited Edition</option>
                    <option value="2" <?=(($config[0]['ribbon'] == 2) ? "selected":"")?>>Discounted</option>
                    <option value="3" <?=(($config[0]['ribbon'] == 3) ? "selected":"")?>>Event</option>
                  </select>
                </div>
            </div>
            <div class="row row-xs align-items-center mg-b-20">
                <div class="col-md-4">
                  <label class="form-label mg-b-0">Discount</label>
                </div>
                <div class="col-md-8 mg-t-5 mg-md-t-0">
                  <input type="text" max="3" class="form-control" name="Discount" placeholder="Enter Discount">
                </div>
            </div>

            <div id="replace<?=$row['ProductNum']?>">
            </div>
          
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-indigo">Submit</button>
            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      $(function(){
        'use strict'

        // showing modal with effect
        $('.modal-effect').on('click', function(e){
          e.preventDefault();
          var effect = $(this).attr('data-effect');
          $('#modal<?=$row['ProductNum']?>').addClass(effect);
        });

        // hide modal with effect
        $('#modaldemo<?=$row['ProductNum']?>').on('hidden.bs.modal', function (e) {
          $(this).removeClass (function (index, className) {
              return (className.match (/(^|\s)effect-\S+/g) || []).join(' ');
          });
        });

      });
    </script>
    <?php
} else {
  ?>
    <div id="modal<?=$row['ProductNum']?>" class="modal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title"><?=$row['ItemName']?></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form method="POST" action="<?=base_url()?>adminpanel/addconfig/<?=$row['ProductNum']?>">
            <div class="row row-xs align-items-center mg-b-20">
                <div class="col-md-4">
                  <label class="form-label mg-b-0">Select Sticker</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control select2" name="Sticker" id="sticker<?=$row['ProductNum']?>" style="width: 100%;">
                    <option value=""></option>
                    <option value="1">Limited Edition</option>
                    <option value="2">Discounted</option>
                    <option value="3">Event</option>
                  </select>
                </div>
            </div>
            <div class="row row-xs align-items-center mg-b-20">
                <div class="col-md-4">
                  <label class="form-label mg-b-0">Discount</label>
                </div>
                <div class="col-md-8 mg-t-5 mg-md-t-0">
                  <input type="text" max="3" class="form-control" name="Discount" placeholder="Enter Discount">
                </div>
            </div>

            <div id="replace<?=$row['ProductNum']?>">
                
            </div>
          
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-indigo">Submit</button>
            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      $(function(){
        'use strict'

        // showing modal with effect
        $('.modal-effect').on('click', function(e){
          e.preventDefault();
          var effect = $(this).attr('data-effect');
          $('#modal<?=$row['ProductNum']?>').addClass(effect);
        });

        // hide modal with effect
        $('#modaldemo<?=$row['ProductNum']?>').on('hidden.bs.modal', function (e) {
          $(this).removeClass (function (index, className) {
              return (className.match (/(^|\s)effect-\S+/g) || []).join(' ');
          });
        });

      });
    </script>
    <?php
}
?>
<script type="text/javascript">
  $("#sticker<?=$row['ProductNum']?>").on("change",function(){
        //Getting Value
        var selValue = $("#sticker<?=$row['ProductNum']?>").val();
        //Setting Value
        //$("#sticker").val(selValue);
        if(selValue == 1){
          $.ajax({
              url:'<?=base_url()?>adminpanel/limitededition',
              method:"POST",
              data:{selValue:selValue},
              success:function(data)
              {
               $('#replace<?=$row['ProductNum']?>').html(data);
              }
           });
        } 
        else
        if(selValue == 2){
          $.ajax({
              url:'<?=base_url()?>adminpanel/discounted',
              method:"POST",
              data:{selValue:selValue},
              success:function(data)
              {

               $('#replace<?=$row['ProductNum']?>').html(data);
              }
           });
        }
        else
        if(selValue == 3){
          $.ajax({
              url:'<?=base_url()?>adminpanel/event',
              method:"POST",
              data:{selValue:selValue},
              success:function(data)
              {
               $('#replace<?=$row['ProductNum']?>').html(data);
              }
           });
        } else {
          $.ajax({
              url:'<?=base_url()?>adminpanel/discounted',
              method:"POST",
              data:{selValue:selValue},
              success:function(data)
              {
               $('#replace<?=$row['ProductNum']?>').html(data);
              }
           });
        }
    });
</script>
<?php
}
?>







<div class="az-content-body">
      <div class="container">
        <?php
        renderview('JRG/Pages/ShopManager/ItemShop/SideMenu')
        ?>
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
               
                        <div class="az-dashboard-one-title">
                          <div>
                            <!--<p class="az-dashboard-text">Your web analytics dashboard template.</p>-->
                          </div>
                          <div class="az-content-header-right">
                            <div class="media">
                              <div class="media-body">
                                <label>Item Section</label>
                                <center><a><h6><div class="typcn icon-default typcn-th-large"></div></h6></a></center>
                              </div><!-- media-body -->
                            </div><!-- media -->
                            <div class="media">
                              <div class="media-body">
                                <label>Item Category</label>
                                <center><a><h6><div class="typcn icon-default typcn-th-large"></div></h6></a></center>
                              </div><!-- media-body -->
                            </div><!-- media -->
                            <div class="media">
                              <div class="media-body">
                                <label>Level Requirements</label>
                                <center><a><h6><?=((!$levelreq) ? "0":"".$levelreq[0]['ShopLevelReq']."")?></h6></a></center>
                              </div><!-- media-body -->
                            </div><!-- media -->
                          </div>
                        </div>
         
    
          <div class="col-md-12">
            <div class="az-content-body">
                <div class="d-flex flex-row mg-b-30">

                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>IMG</th>
                          <th>Item</th>
                          <th>Price</th>
                          <th>Status</th>
                          <th>Sticker</th>
                          <th>Discount</th>
                          <th>Sub Item</th>
                          <th>GM Pick</th>
                          <td>Date Limit</td>
                        </tr>
                      </thead>
                      <tbody style="font-size: 11px;">
                        <?php
                        $sticker0 = "";
                        foreach ($prod as $row) {
                          $prod1 = $this->llllllllz_model->getshopcfg($row['ProductNum']);
                          if($prod1){
                            $sticker = $prod1[0]['ribbon'];
                            if($sticker == 1){
                              $sticker0 = "<span class='badge badge-success'>Limited Edition</span>";
                            } elseif($sticker == 2){
                              $sticker0 = "<span class='badge badge-discounted'>Discounted</span>";
                            } elseif($sticker == 3){
                              $sticker0 = "<span class='badge badge-event'>Event</span>";
                            }

                            if($prod1[0]['discount'] != NULL){
                              $discount = $row['ItemPrice'] * ($prod1[0]['discount'] / 100);
                              $price =  $row['ItemPrice'] - $discount;
                              $output = '<span class="price">'.$price.'</span> (<em>'.$prod1[0]['discount'].'%</em>)';
                            } else {
                              $output = "";
                              $discount = 0;
                            }
                            

                             $price =  $row['ItemPrice'] - $discount;
                            
                            if($prod1[0]['ItemSub'] != NULL){
                              if($this->llllllllz_model->get_productbyimg($prod1[0]['ItemSub']) == ""){
                                $img = '<img style="height:25px; width:25px; border: 1px solid skyblue;" src="'.base_url().'Images/ItemShopNew/Default.jpg" data-toggle="tooltip" data-placement="bottom" title="'.$this->llllllllz_model->get_productbyname($prod1[0]['ItemSub']).'">';
                              } else {
                                $img = '<img style="height:25px; width:25px; border: 1px solid skyblue;" src="'.base_url().'Uploadfiles/ItemShop/'.$this->llllllllz_model->get_productbyimg($prod1[0]['ItemSub']).'" data-toggle="tooltip" data-placement="bottom" title="'.$this->llllllllz_model->get_productbyname($prod1[0]['ItemSub']).'">';
                              }

                              $itemsub = $img;
                            } else {

                              $itemsub = "";
                            }


                            if($prod1[0]['ItemPeriod'] != NULL){
                              $ItemPeriod = $prod1[0]['ItemPeriod'];
                            } else {
                              $ItemPeriod = "";
                            }
                            
                          } else {
                            $sticker0 = "";
                            $price = "";
                            $itemsub = "";
                            $output = "";
                            $ItemPeriod = "";
                          }

                          echo '<tr style="cursor:pointer;" href="#modal'.$row['ProductNum'].'" class="modal-effect" data-toggle="modal" data-effect="effect-scale">
                                <td><img id="blah"  style="height:25px; width:25px; border: 1px solid skyblue;"  '.(($row['ItemSS'] == "") ? 'src="'.base_url().'Images/ItemShopNew/Default.jpg"':'src="'.base_url().'Uploadfiles/ItemShop/'.$row['ItemSS'].'"').' data-toggle="tooltip" data-placement="bottom" title="'.$row['ItemName'].'"></td>
                                <td>'.$row['ItemName'].'</td>
                                <td align="center"><span class="price">'.$row['ItemPrice'].'</span></td>
                                <td align="center">'.(($row['hidden'] == 0) ? '<span class="badge badge-dark">Visible</span>':'<span class="badge badge-light">Hidden</span>').'</td>
                                <td align="center">'.$sticker0.'</td>
                                <td align="center">'.(($prod1) ? ''.$output.'':"").'</td>
                                <td align="center">'.$itemsub.'</td>
                                <td></td>
                                <td>'.$ItemPeriod.'</td>
                                </tr>';
                        }
                        ?>
                      </tbody>
                    </table>
              </div>

                </div>
            </div><!-- az-content-body --> 
          </div>
         </div>
      </div><!-- container -->
    </div>



  <script>
      $(function(){
        'use strict'

        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tooltip-secondary"]').tooltip({
          template: '<div class="tooltip tooltip-secondary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });

      });
    </script>