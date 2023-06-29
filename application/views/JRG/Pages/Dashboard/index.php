<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<div class="az-content-body">
      <div class="row row-sm">
            <div class="col-sm-6 col-xl-3">
              <div class="card card-dashboard-twentytwo">
                <div class="media">
                  <div class="media-icon bg-danger"><i class="fab fa-php"></i></div>
                  <div class="media-body">
                    <h6 style="font-size: 22px;">PHP VERSION</h6>
                    <span><?=phpversion()?></span>
                  </div>
                </div>
              </div><!-- card -->
            </div><!-- col -->
            <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
              <div class="card card-dashboard-twentytwo">
                <div class="media">
                  <div class="media-icon bg-primary"><i class="fas fa-laptop"></i></div>
                  <div class="media-body">
                    <h6>OS</h6>
                    <span><?=getOS($_SERVER['HTTP_USER_AGENT'])?></span>
                  </div>
                </div>
              </div><!-- card -->
            </div><!-- col-3 -->
            <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
              <div class="card card-dashboard-twentytwo">
                <div class="media">
                  <div class="media-icon bg-success"><i class="fas fa-cogs"></i></div>
                  <div class="media-body">
                    <h6>PANEL</h6>
                    <span><?=CI_VERSION;?></span>
                  </div>
                </div>
              </div><!-- card -->
            </div><!-- col -->
            <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
              <div class="card card-dashboard-twentytwo">
                <div class="media">
                  <div class="media-icon bg-warning"><i class="fa fa-fw fa-info-circle"></i></div>
                  <div class="media-body">
                    <h6>TICKETS</h6>
                    <span>0</span>
                  </div>
                </div>
              </div><!-- card -->
            </div><!-- col -->



          <div class="col-sm-4 mg-t-20">
          <div class="card card-dashboard-eight bg-white">
          <h6 class="card-title" style="float:left;">Server Population</h6>
                <span class="d-block mg-b-20" style="font-weight:bold;">Online: <b style="color:#32cd32;" id="refresh"><?=$this->Internal_model->GetPlayerOnline();?></b></span>

                <table  class="table table-bordered mg-b-10" style="font-size:12px;">
                <tr>
                <th></th>
                <?php 
                foreach (schools() as $row) {
                  echo '<th>'.school($row).'</td>';
                }
                ?>
                </tr>
                <?php
               
                $ctr = 1;
                foreach (CJob() as $row) {
                  if($ctr == 1){
                    $output0 = $info0[0]['brawler'];
                    $output1 = $info1[0]['brawler'];
                    $output2 = $info2[0]['brawler'];
                  } elseif($ctr == 2){
                    $output0 = $info0[0]['swordsman'];
                    $output1 = $info1[0]['swordsman'];
                    $output2 = $info2[0]['swordsman'];
                  } elseif($ctr == 3){
                    $output0 = $info0[0]['archer'];
                    $output1 = $info1[0]['archer'];
                    $output2 = $info2[0]['archer'];
                  } elseif($ctr == 4){
                    $output0 = $info0[0]['shaman'];
                    $output1 = $info1[0]['shaman'];
                    $output2 = $info2[0]['shaman'];
                  } elseif($ctr == 5){
                    $output0 = $info0[0]['extreme'];
                    $output1 = $info1[0]['extreme'];
                    $output2 = $info2[0]['extreme'];
                  } elseif($ctr == 6){
                    $output0 = $info0[0]['gunner'];
                    $output1 = $info1[0]['gunner'];
                    $output2 = $info2[0]['gunner'];
                  } elseif($ctr == 7){
                    $output0 = $info0[0]['assassin'];
                    $output1 = $info1[0]['assassin'];
                    $output2 = $info2[0]['assassin'];
                  } elseif($ctr == 8){
                    $output0 = $info0[0]['magician'];
                    $output1 = $info1[0]['magician'];
                    $output2 = $info2[0]['magician'];
                  } elseif($ctr == 9){
                    $output0 = $info0[0]['shaper'];
                    $output1 = $info1[0]['shaper'];
                    $output2 = $info2[0]['shaper'];
                  }
                  
                  if($output0 == 0 && $output1 == 0 && $output2 == 0){

                  } else {
                    echo '
                    <tr>
                      <td align="center">'.$row.'</td>
                      <td align="center">'.(($output0 > 0) ? "".$output0."":"0").'</td>
                      <td align="center">'.(($output1 > 0) ? "".$output1."":"0").'</td>
                      <td align="center">'.(($output2 > 0) ? "".$output2."":"0").'</td>
                      ';
                  }
                  
                    $ctr++;
                  }
                  ?>
                  </tr>
                  <tr>
                  </table>

          </div>
          </div><!-- col-3 -->


          <div class="col-sm-4 mg-t-20">
              
          </div><!-- col-3 -->


          <div class="col-sm-4 mg-t-20">
                <div class="card card-dashboard-eight bg-white">
                <h6 class="card-title" style="float:left;">Panel Logs</h6>
                
                <table  class="table table-bordered mg-b-10" style="font-size:12px;">
                <!-- <thead>
                  <tr>
                    <th></th>
                  </tr>
                </thead> -->
                <tbody>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
               
                </table>
                </div>
          </div>

      </div>
</div>


  <script>
    setInterval(function(){
      $('#refresh').load('<?=base_url()?>adminpanel/GetUserOnline');
    },5000);


      $(function(){
        'use strict'

        $('[data-toggle="tooltip"]').tooltip();

        // colored tooltip
        $('[data-toggle="tooltip-primary"]').tooltip({
          template: '<div class="tooltip tooltip-primary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });

        $('[data-toggle="tooltip-secondary"]').tooltip({
          template: '<div class="tooltip tooltip-secondary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });

      });
    </script>
