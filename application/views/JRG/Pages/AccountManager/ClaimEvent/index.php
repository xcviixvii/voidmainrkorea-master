<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<div class="az-content-body">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
   
             <div class="row">
                <div class="col-md-8">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover mg-b-10">
                      <thead>
                        <tr>
                          <th>Code</th>
                          <th>Points</th>
                          <th>Product</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($ClaimCode as $row) {
                          echo '<tr>
                                <td>'.$row['Code'].'</td>
                                <td>'.(($row['Points']) ? "".$row['Points']."":"NULL").'</td>
                                <td>'.(($row['ProductNum']) ? "".$row['ProductNum']."":"NULL").'</td>
                                </tr>';
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="col-md-4">
                   <form method="POST" data-parsley-validate>
                    <div class="pd-30 pd-sm-40 bg-gray-200 bd bd-2">

                        
                        <div class="form-group">
                          <label class="form-label">Code:</label>
                          <input type="text" name="Code" class="form-control wd-250" placeholder="Code" maxlength="6" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">Select Type:</label>
                          <select class="form-control select2-no-search" id="stype" name="stype" required>
                            <option ></option>
                            <option value="1">E-Points</option>
                            <option value="2">Product</option>
                          </select>
                        </div><!-- form-group -->


                        <div class="form-group">
                            <div id="replace">
                            
                            </div>
                        </div>
                        
                        <br />
                      <button type="submit" class="btn btn-az-primary pd-x-20">Save</button>
                    </div>
                  </form>

                </div>
              </div>
              

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 

<script type="text/javascript">
$("#stype").change(function(){
  var stype = $(this).val();
  
  if(stype == ""){
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/stypedefault',
      data: {stype: stype},
        success:function(data){
      $('#replace').html(data);
      }
    });
  } else if(stype == 1) {
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/ClaimEPoints',
      data: {stype: stype},
        success:function(data){
      $('#replace').html(data);
      }
    });
  } else if(stype == 2) {
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>adminpanel/ClaimItems',
      data: {stype: stype},
        success:function(data){
      $('#replace').html(data);
      }
    });
  }
    
});
</script>