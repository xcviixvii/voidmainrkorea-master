<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?>
</title>
<style type="text/css">
  input[type="file"] {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  width: 100%;
  margin: 0;
  padding: 0;
  font-size: 1px;
  cursor: pointer;
  opacity: 0;
  filter: alpha(opacity=0);
}

.inputfile + label span {
  width: 170px;
  min-height: 20px;
  display: inline-block;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  vertical-align: top;
  border: 1px solid #ccc;
}


.inputfile + label strong {
  height: 100%;
  color: #fff;
  background-color: #d3394c;
  display: inline-block;
}
.inputfile + label span{
  height: 40px;
  padding: 9px;
}

.inputfile + label strong {
  padding: 10px;
}
.inputfile + label svg {
  width: 1em;
  vertical-align: middle;
  fill: currentColor;
  margin-top: -0.25em;
  margin-right: 0.25em;
}

.box {
  position: relative;
}
</style>


<div class="az-content-body">
      <div class="container">
        <?php
        renderview('JRG/Pages/ShopManager/ItemShop/SideMenu')
        ?>
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <form method="POST" id="form" enctype="multipart/form-data" data-parsley-validate>

            <div class="row">
               <div class="col-2">
                <div class="form-group">
                  <label class="form-label">Item Main:</label>
                  <input type="text" name="ItemMain" id="ItemMain" class="form-control" placeholder="Item Main" required autocomplete="off">
                </div><!-- form-group -->
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label class="form-label">Item Sub:</label>
                  <input type="text" name="ItemSub" id="ItemSub" class="form-control" placeholder="Item Sub" required autocomplete="off">
                </div><!-- form-group -->
              </div>
              <div class="col-4">
                <label class="form-label">Item Image:</label>
         
                <div class="box">
                  <input type="file" name="ItemSS" id="file" class="inputfile" style="padding: 20px;">
                  <label for="file"><span></span> <strong>Select a file</strong></label>
              </div>
              </div>

              <div class="col-4">
                <img id="blah"  style="height:100px; width:100px; border: 2px solid skyblue;"  src="<?=base_url()?>Images/ItemShopNew/Default.jpg" >
              </div>

            </div>
            <div class="row">
              <div class="col-12">
                <hr class="mg-y-10"> 
              </div>
            </div>
            <div class="row">
             
              <div class="col-4">
                <div class="form-group">
                  <label class="form-label">Item Name:</label>
                  <input type="text" name="ItemName" id="ItemName" class="form-control wd-250" placeholder="Item Name" required autocomplete="off">
                </div><!-- form-group -->
              </div>

              <div class="col-4">
                <div class="form-group">
                  <label class="form-label">Item Section:</label>
                  <select class="form-control select2-no-search" name="ItemSec" id="section" required>
                    <option selected></option>
                  <?php
                  foreach ($section as $sec){
                    echo '<option value="'.$sec['secid'].'">'.$sec['sectionname'].'</option>';
                  }
                  ?>
                  </select>
                </div><!-- form-group -->
              </div>

              <div class="col-4">
                <div class="form-group">
                  <div id="replace">
                    <label class="form-label">Item Category:</label>
                    <select class="form-control select2-no-search">
                    </select>
                  </div>
                </div><!-- form-group -->
              </div>


              <div class="col-12">
                <hr class="mg-y-10"> 
              </div>

              <div class="col-4">
                <div class="form-group">
                  <label class="form-label">Item Price:</label>
                  <input type="text" name="ItemPrice" id="ItemPrice" class="form-control" placeholder="Item Price" required autocomplete="off">
                </div><!-- form-group -->
              </div>

              <div class="col-4">
                <div class="form-group">
                  <label class="form-label">Item Stock:</label>
                  <input type="text" name="ItemStock" id="ItemStock" class="form-control" placeholder="Item Stock" required required autocomplete="off">
                </div><!-- form-group -->
              </div>

              <div class="col-4">
                <label class="form-label">Item Visible:</label>
                <input type="hidden" id="itemvisible" value="0" name="itemvisible">
                <div class="az-toggle on"><span></span></div>
              </div>

              <div class="col-12">
                <hr class="mg-y-10"> 
              </div>

              <div class="col-4">
                <div class="form-group">
                  <label class="form-label">Item Class:</label>
                  <select class="form-control select2-no-search" name="ItemDisc" id="ItemDisc" required>
                    <option selected></option>
                  <?php
                  $ctr = 1;
                  foreach (itemclass() as $c){
                    echo '<option value="'.$ctr.'">'.$c.'</option>';
                    $ctr++;
                  }
                  ?>
                  </select>
                </div><!-- form-group -->
              </div>
              
              <div class="col-4">
                <div class="form-group">
                  <label class="form-label">Item Config:</label>
                  <select class="form-control select2-no-search" name="ItemCfg" id="ItemCfg">
                    <?php
                      foreach (ItemConfig() as $c){
                        echo '<option value="'.$c['Value'].'">'.$c['Name'].'</option>';
                      }
                    ?>
                  </select>
                </div><!-- form-group -->
              </div>

              <div class="col-4">
                      <div class="form-group">
                        <div id="ItemMallreplace">
                          <label class="form-label">Item Mall Config:</label>
                          <select class="form-control select2-no-search">
                          </select>
                        </div>
                      </div>
              </div>

              <div class="col-12">
                <hr class="mg-y-10"> 
              </div>

            </div>

              <input type="hidden" name="newsid" id="c_newsid" />
              <label class="form-label">Item Description:</label>
              <textarea id="editor1" class="content" name="ItemComment"></textarea>
              <hr class="mg-y-30">
              <button type="submit" class="btn btn-az-primary pd-x-20">Add Item</button>
              
          </form>
        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>

<script type="text/javascript">
'use strict';

$('.az-toggle').on('click', function(){
          $('#itemvisible').val() == "0" ? one() : zero();;
          $(this).toggleClass('on');
});

function one() {
    $('#itemvisible').val("1");
    // do play
}

function zero() {
    $('#itemvisible').val("0");
    // do pause
}

;( function ( document, window, index )
{
  var inputs = document.querySelectorAll( '.inputfile' );
  Array.prototype.forEach.call( inputs, function( input )
  {
    var label = input.nextElementSibling,
    labelVal = label.innerHTML;

    input.addEventListener( 'change', function( e )
    {
      var fileName = '';
      if( this.files && this.files.length > 1 )
        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
      else
        fileName = e.target.value.split( '\\' ).pop();

      if( fileName )
        label.querySelector( 'span' ).innerHTML = fileName;
      else
        label.innerHTML = labelVal;
    });

    // Firefox bug fix
    input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
    input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
  });
}( document, window, 0 ));
</script>


<script type="text/javascript">
$("#ItemCfg").change(function(){
  var ItemCfg = $(this).val();
  
  if(ItemCfg != 3){
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/ItemMallCfgDefault',
      data: {ItemCfg: ItemCfg},
        success:function(data){
      $('#ItemMallreplace').html(data);
      }
    });
  } else {
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/OpenItemMallCategory',
      data: {ItemCfg: ItemCfg},
        success:function(data){
      $('#ItemMallreplace').html(data);
      }
    });
  }
    
});
</script>



<script type="text/javascript">
$("#section").change(function(){
  var secvalue = $(this).val();
  if(secvalue == ""){
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/categorydefault',
      data: {secvalue: secvalue},
        success:function(data){
      $('#replace').html(data);
      }
    });
  } else {
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/opencategory/' + secvalue,
      data: {secvalue: secvalue},
        success:function(data){
      $('#replace').html(data);
      }
    });
  }
    
});
</script>



<script>
  $(function () {
    CKEDITOR.replace('editor1')
  })
</script>


<script type="text/javascript">
         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".inputfile").change(function() {
  readURL(this);
});
</script>
