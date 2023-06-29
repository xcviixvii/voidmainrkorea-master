<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>
<div class="az-content-body">
       <div class="container maxheight" >
        <div class="az-content-left az-content-left-mail">
        <div class="az-mail-menu">
                <a href="<?=base_url()?>adminpanel/SendItemBank"><strong>Send To Many</strong></a> 
                <br />
                <a href="<?=base_url()?>adminpanel/SendItemBank/ByUserName"><strong>Send By UserName</strong></a>
                <br />
                <a href="<?=base_url()?>adminpanel/SendItemBank/ByCharacter"><strong>Send By Character Name</strong></a>
        </div>
        </div>
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
   
              <div class="row">
                <div class="col-md-5">
                 <form method="post" action="<?=base_url()?>adminpanel/doinsertitembank/">
                      
                        <div class="form-group">
                        
                          <label>Product</label>
                          <select name="item" class="form-control selectitem select2-no-search">
                            <option></option>
                          <?php
                          foreach ($itemshop as $row) {
                            echo '<option value="'.$row['ProductNum'].'">'.$row['ItemName'].'</option>';
                          }
                          ?>
                          </select>
                          
                          <label>Status</label><br />
                          <input type="hidden" name="checkonline" value="0">
                          <input type="checkbox" name="checkonline" value="1" class="flat-red" id="etomagcchange">
                          Online
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <button type="submit" class="btn btn-info">send</button><br />
                        <small></small>
                      </div>
                    </form>

                </div>
              </div>
                     
        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>