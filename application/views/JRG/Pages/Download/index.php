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
                          <th>download Link</th>
                          <th>action</th>

                        </tr>
                      </thead>
                      <tbody style="font-size: 11px;">
                        <?php
                        if($download){
                          $ctr = 0;
                          $client = 0;
                          $test = 0;
                          $manual = 0;
                          $value = "";
                          foreach ($download as $row) {
                            if($row['DownloadType'] == 'Game Client'){
                              $client++;
                              $value = $row['DownloadType'].' '.$client;
                            }

                            if($row['DownloadType'] == 'Test Client'){
                              $test++;
                              $value = $row['DownloadType'].' '.$test;
                            }

                            if($row['DownloadType'] == 'Manual Patch'){
                              $manual++;
                              $value = $row['DownloadType'].' '.$manual;
                            }

                            echo '<tr>
                            <td><a href="'.$row['DownloadLink'].'">'.$value.'</a></td>
                            <td>
                            '.EditDelete($row['downloadid'],''.base_url().'AdminPanel/DeleteFunction/'.VoidEncrypter2('Delete Download').'/'.VoidEncrypter2($row['downloadid']).'/'.VoidEncrypter2('Download Link Successfully Deleted').'/'.VoidEncrypter2('Download').'').'
                            </td>
                            </tr>';

                            //<a href=""><i class="fas fa-edit"></i></a>
                            
                          }
                        } else {
                          echo '<tr><td colspan="9999" align="center">No Record Found....</td></tr>';
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
                          <label class="form-label">Download Link:</label>
                          <input type="text" name="downloadlink" class="form-control wd-250" placeholder="Download Link" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">Download Type:</label>
                          <div id="slWrapper" class="parsley-select wd-sm-250">
                            <select class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" name="downloadtype" required>
                              <option label="Download Type"></option>
                              <option value="Game Client">Game Client</option>
                              <option value="Test Client">Test Client</option>
                              <option value="Manual Patch">Manual Patch</option>
                            </select>
                            <div id="slErrorContainer"></div>
                          </div>
                        </div><!-- form-group -->
                        <br />
                      <button type="submit" class="btn btn-az-primary pd-x-20">Save</button>
                    </div>
                  </form>
                  </div>

                </div>
              </div>
              

        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>

<script>
        Table = '<?=VoidEncrypter2('download')?>';
        Flds = '<?=VoidEncrypter2('DownloadID')?>';
        Msg = '<?=VoidEncrypter2('Download Link Successfully Updated')?>';
        Rd = '<?=VoidEncrypter2('adminpanel/Download')?>';
        opt = '<?=VoidEncrypter2('Game Client,Test Client,Manual Patch')?>';
        Fields = [];
        Fields[0] = '<?=VoidEncrypter2('DownloadLink-1')?>';
        Fields[1] = '<?=VoidEncrypter2('DownloadType-2')?>';
</script>

<script>
      $(function(){
        'use strict'
        $(document).ready(function(){
          $('.select2').select2({
            placeholder: 'Download Type'
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