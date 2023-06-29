<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

    <link rel="stylesheet" href="<?=base_url()?>Library/lightbox/css/lightbox.css">
    <!-- Lightbox style -->
    <script src="<?=base_url()?>Library/lightbox/js/lightbox-2.6.min.js"></script> 

<div class="az-content-body">
    
        <div class="container maxheight" >
        <div class="az-content-left az-content-left-mail" style="width:300px;">
        <div class="az-mail-menu">
            <div class="pd-30 pd-sm-40 bg-white-250 bd bd-1">

                    <form method="POST" enctype="multipart/form-data" data-parsley-validate>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="filename" class="custom-file-input" id="customFile" require>
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div><!-- form-group -->


                        <div class="form-group">
                          <label class="form-label">Url:</label>
                          <input type="text" name="Url" class="form-control" placeholder="Url" required>
                        </div><!-- form-group -->

                        <br />
                      <button type="submit" class="btn btn-success pd-x-20 ">Save</button>
                    </form>
            </div>
        </div>
        </div>


        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Url</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $ctr = 0;
                        foreach ($Slider as $row) {
                            $ctr++;
                            echo '<tr>
                                <td>'.$ctr.'</td>
                                <td align="center">
                                <a href="'.base_url().'Uploadfiles/Slider/'.$row['Image'].'" data-lightbox="example-1">
                                <img src="'.base_url().'Uploadfiles/Slider/'.$row['Image'].'" style="width:90px;" class="wd-50p wd-sm-50 rounded mg-sm-r-20 mg-b-20 mg-sm-b-0">
                                </a>
                                </td>
                                <td>'.$row['Url'].'</td>
                                <td>
                                '.EditDelete('#',''.base_url().'AdminPanel/DeleteFunction/'.VoidEncrypter2('Delete Slider').'/'.VoidEncrypter2($row['SliderID']).'/'.VoidEncrypter2('Slider Successfully Deleted').'/'.VoidEncrypter2('Slider').'').'
                                
                                </td>
                            </tr>';
                        }
                    ?>
                </tbody>

            </table>
        </div>
    </div><!-- container -->


</div><!-- az-content-body --> 
