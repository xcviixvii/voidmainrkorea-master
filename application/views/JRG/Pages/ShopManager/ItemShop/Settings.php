<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?>
</title>
<style type="text/css">
  #section_list li.ui-state-highlight
   {
    padding:22px;
    background-color: skyblue;
    border:1px dotted #ccc;
    cursor:move;
    list-style: none;
   }

   #section_list1 li.ui-state-highlight
   {
    padding:22px;
    background-color: skyblue;
    border:1px dotted #ccc;
    cursor:move;
    list-style: none;
   }
</style>
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
            <form id="form">
            <div class="az-content-header-right">
              <div class="media">
                <div class="media-body">
                  <label>Level Requirements</label>
                  <center><input type="text" name="levelreq" onClick="this.select();" id="myInput" class="form-control wd-sm-100 num" style="height: 17px; border: 0;background: transparent; text-align: center; font-weight: 500; color:#1c273c;" value="<?=((!$levelreq) ? "0":"".$levelreq[0]['ShopLevelReq']."")?>"></center>
                </div><!-- media-body -->
              </div><!-- media -->
            </div>
            </form>
          </div>
         
          <div class="row row-sm">
            <div class="col-md-5">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                  Item Section
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0">
                  <form method="POST" action="<?=base_url()?>adminpanel/additemsection" data-parsley-validate>
                  <div class="row row-xs">
                    <div class="col-md-9">
                      <input type="text" name="itemsec" class="form-control" placeholder="Item Section" required>
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

            <div class="col-md-7">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                  Item Category
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0 ">
                  <form method="POST" action="<?=base_url()?>adminpanel/addcategory" data-parsley-validate>
                  <div class="row row-xs">
                    <div class="col-md-5">
                      <div id="slWrapper" class="parsley-select">
                        <select name="secid" class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                        <option value="" selected=""></option>
                        <?php
                        foreach ($section as $row) {
                          echo '<option value="'.$row['secid'].'">'.$row['sectionname'].'</option>';
                        }
                        ?>
                        </select>
                        <div id="slErrorContainer"></div>
                      </div>
                      
                    </div><!-- col -->
                    <div class="col-md-5">
                      <input type="text" name="categoryname" class="form-control" placeholder="Item Category" required>
                    </div><!-- col -->
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-az-primary btn-sm btn-block"><i class="fas fa-plus"></i></button>
                    </div><!-- col -->
                    <hr class="mg-y-30">
                  </div>
                  </form>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->
          </div>



          <div class="row row-sm mg-t-20">
            <div class="col-md-5">
              <div class="card">
                <div class="card-body">
                  <div class="az-content-label mg-b-5">Section Position</div>
                  <p class="mg-b-20">Double Click to Select Section</p>
                  <ul class="list-group" id="section_list">
                    <?php
                    foreach ($sectionbysort as $row) {
                      echo ' <li id="'.$row["secid"].'" class="list-group-item list-group-item-action" ondblclick="myFunction('.$row['secid'].');"><span>'.$row['sectionname'].'</span></li>
                      ';

                    }
                    ?>
                  </ul>

      

                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->

            <div class="col-md-7">
              <div class="card">
                <div class="card-body">
                  <div class="az-content-label mg-b-5">Category Position</div>
                  <p class="mg-b-20">Drag to Edit The Position.</p>
                  <div id="replace"></div>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->
          </div>


        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>



 <script src="<?=base_url()?>Scripts/jquery-ui.min.js"></script>
 <script type="text/javascript">
 $(".num").on("keypress keyup blur",function (event) {  
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
            var levelreq = document.getElementById('myInput').value;
             $.ajax({
              url:"<?=base_url()?>adminpanel/updatelevel",
              method:"POST",
              data:{levelreq:levelreq},
              success:function(data)
              {
               //alert(data);
              }
             });
        });

 window.onload = function() {
 const myInput = document.getElementById('myInput');
 myInput.onpaste = function(e) {
   e.preventDefault();
 }


}
 </script>
     <script>
      $(function(){
        'use strict'
        $(document).ready(function(){
          $('.select2').select2({
            placeholder: 'Choose one'
          });

          $('.select2-no-search').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Choose one'
          });
        });

        $('#selectForm').parsley();
        $('#selectForm2').parsley();

      });
    </script>

<script>
$(document).ready(function(){
 $( "#section_list" ).sortable({
  revert: true,
  cancel: "#section_list li span",
  placeholder : "ui-state-highlight",
  update  : function(event, ui)
  {
   var page_id_array = new Array();
   $('#section_list li').each(function(){
    page_id_array.push($(this).attr("id"));
   });
   $.ajax({
    url:"<?=base_url()?>adminpanel/sorting",
    method:"POST",
    data:{page_id_array:page_id_array},
    success:function(data)
    {
     //alert(data);
    }
   });
  }
 });

});
</script>

<script type="text/javascript">
  function myFunction(e) {
    var value = e;
      $.ajax({
        url:'<?=base_url()?>adminpanel/aligncategory',
        method:"POST",
        data:{value:value},
        success:function(data)
        {
         $('#replace').html(data);
        }
       });
    
  }
</script>