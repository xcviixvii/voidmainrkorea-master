<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>
<div class="az-content-body">
      <div class="container">
        <?php renderview('JRG/Pages/News/SideMenu')?>
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="table-responsive">
            <table class="table az-table-reference mg-t-0">
              <thead>
                <tr>
                  <th width="10%">#</th>
                  <th width="60%">Title</th>
                  <th width="10%">Banner</th>
                  <th width="20%">Date</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody>

                <?php 
                $ctr = 1;
                if($news){
                  foreach ($news as $row){ ?>
                    <tr>
                      <td><?=$ctr?></td>
                      <td>
                        <?php 
                              $string = strip_tags($row['newstitle']);
                              echo substr($string,0,50).''.((strlen($row['newstitle']) > 50) ? "...":"").'';
                        ?>         
                      </td>
                      <td align="center"><img class="wd-50p wd-sm-50 rounded mg-sm-r-20 mg-b-20 mg-sm-b-0" src="<?=base_url()?>Uploadfiles/News/<?=$row['banner']?>" alt=""></td>
                      <td align="center"><?=formatdate2($row['datestamp'])?></td>
                      <td align="center">
                      <a href="<?=base_url()?>adminpanel/News/EditNews/<?=$row['newsid']?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit News"><i class="fas fa-edit"></i></a>
                      <a href="<?=base_url()?>adminpanel/DeleteNews/<?=$row['newsid']?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete News"><i class="fas fa-trash-alt"></i></a>
                      </td>
                    </tr>
                  <?php 
                  $ctr++;
                  } 
                } else {
                  ?>
                  <tr>
                    <td colspan="5" align="center"> No Record Found...</td>
                  </tr>
                  <?php
                } 
                ?>
              </tbody>
            </table>
          </div><!-- table-responsive -->


        </div><!-- az-content-body -->
      </div><!-- container -->
    </div>

 