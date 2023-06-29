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
                          <th>Pin</th>
                          <th>EPoints</th>
                          <th>Date Stamp</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($TopUp as $row) {
                          


                            echo '<tr>
                            <td>'.$row['TopUpCode'].'</td>
                            <td>'.$row['TopUpPin'].'</td>
                            <td>'.$row['EPoints'].'</td>
                            <td>'.formatdate1($row['datetimestamp']).'</td>
                            <td>
                            '.EditDelete($row['TopUpCID'],''.base_url().'AdminPanel/DeleteFunction/'.VoidEncrypter2('Delete Code').'/'.VoidEncrypter2($row['TopUpCID']).'/'.VoidEncrypter2('Code Successfully Deleted').'/'.VoidEncrypter2('TopUp').'').'
                            </td>
                            </tr>';
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="col-md-4">
                  <div id="replace">
                   <form method="POST" data-parsley-validate>
                    <div class="pd-30 pd-sm-40 bg-gray-200 bd bd-2">
                    
                        <div class="form-group">
                          <label class="form-label">Top Up Code:</label>
                          <input type="text" name="Code" class="form-control wd-250" value="<?=generatecode()?>" placeholder="Code" maxlength="11" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">Top Up Pin:</label>
                          <input type="text" name="Pin" class="form-control wd-250" value="<?=generatepincode()?>" placeholder="Pin" maxlength="6" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">E Points:</label>
                          <input type="text" name="EPoints" class="form-control wd-250" placeholder="EPoints" maxlength="6" required>
                        </div><!-- form-group -->


                        
                        <br />
                      <button type="submit" class="btn btn-az-primary pd-x-20">Save</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>
              

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 
<script>
        Table = '<?=VoidEncrypter2('pTopUpCode')?>';
        Flds = '<?=VoidEncrypter2('TopUpCID')?>';
        Msg = '<?=VoidEncrypter2('Successfully TopUp Code Updated')?>';
        Rd = '<?=VoidEncrypter2('adminpanel/TopUp')?>';
        opt = '';
        Fields = [];
        Fields[0] = '<?=VoidEncrypter2('TopUpCode-1')?>';
        Fields[1] = '<?=VoidEncrypter2('TopUpPin-1')?>';
        Fields[2] = '<?=VoidEncrypter2('EPoints-1')?>';
</script>