<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<style>
ul#menu li {
  display: inline-table;
}
</style>

<div class="az-content-body">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
            <div class="row">
                <?php
                if(count($CPData) == 0){
                ?>
                <div class="col-md-12">
                    <form method="POST" data-parsley-validate>
                        <table class="table">
                        <tr>
                            <td colspan="3">
                                <div class="form-group">
                                <label class="form-label">Schedule:</label>
                                <select name="Schedule" class="select2 wd-250">
                                <option label="Select time">Select time</option>
                                <option value="00:00">00:00</option>
                                <option value="00:30">00:30</option>
                                <option value="01:00">01:00</option>
                                <option value="01:30">01:30</option>
                                <option value="02:00">02:00</option>
                                <option value="02:30">02:30</option>
                                <option value="03:00">03:00</option>
                                <option value="03:30">03:30</option>
                                <option value="04:00">04:00</option>
                                <option value="04:30">04:30</option>
                                <option value="05:00">05:00</option>
                                <option value="05:30">05:30</option>
                                <option value="06:00">06:00</option>
                                <option value="06:30">06:30</option>
                                <option value="07:00">07:00</option>
                                <option value="07:30">07:30</option>
                                <option value="08:00">08:00</option>
                                <option value="08:30">08:30</option>
                                <option value="09:00">09:00</option>
                                <option value="09:30">09:30</option>
                                <option value="10:00">10:00</option>
                                <option value="10:30">10:30</option>
                                <option value="11:00">11:00</option>
                                <option value="11:30">11:30</option>
                                <option value="12:00">12:00</option>
                                <option value="12:30">12:30</option>
                                <option value="13:00">13:00</option>
                                <option value="13:30">13:30</option>
                                <option value="14:00">14:00</option>
                                <option value="14:30">14:30</option>
                                <option value="15:00">15:00</option>
                                <option value="15:30">15:30</option>
                                <option value="16:00">16:00</option>
                                <option value="16:30">16:30</option>
                                <option value="17:00">17:00</option>
                                <option value="17:30">17:30</option>
                                <option value="18:00">18:00</option>
                                <option value="18:30">18:30</option>
                                <option value="19:00">19:00</option>
                                <option value="19:30">19:30</option>
                                <option value="20:00">20:00</option>
                                <option value="20:30">20:30</option>
                                <option value="21:00">21:00</option>
                                <option value="21:30">21:30</option>
                                <option value="22:00">22:00</option>
                                <option value="22:30">22:30</option>
                                <option value="23:00">23:00</option>
                                <option value="23:30">23:30</option>
                                </select>
                                </div><!-- form-group -->
                            </td>
                        </tr>

                        <tr>
                            <td width="25%">
                                <div class="form-group">
                                <label class="form-label">Default Points:</label>
                                <input type="text" class="form-control wd-250" name="DPoints" placeholder="Default Points" require>
                                </div><!-- form-group -->
                            </td>

                            <td width="25%">
                                <div class="form-group">
                                <label class="form-label">Date From:</label>
                                <input type="text" class="form-control fc-datepicker wd-250" name="DateFrom" placeholder="MM/DD/YYYY" style="cursor:pointer" readonly>
                                </div><!-- form-group -->
                            </td>

                            <td width="25%">
                                <div class="form-group">
                                <label class="form-label">Date To:</label>
                                <input type="text" class="form-control fc-datepicker wd-250" name="DateTo" placeholder="MM/DD/YYYY" style="cursor:pointer" readonly>
                                </div><!-- form-group -->
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" align="right">
                                <button type="submit" class="btn btn-az-primary pd-x-20">Save</button>
                            </td>
                        </tr>
                        </table>
                 

                        <br />
                      
                    
                  </form>




                  
                </div> <!-- END COLUMN -->
                
                
                <?php
                } else {

                
                ?>
                

                <div class="col-md-4">
                <table class="table table-bordered" style="font-size:12px;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Club</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $ctr = 0;
                        foreach ($Score as $row) {
                            $getBadge = $this->llllllllz_model->getGuild($row['GuNum']);
                            $ctr++;

                            if($ctr == 1){
                                $score = '<b style="color:#ffd700;">'.$row['Score'].'</b>';
                            } elseif($ctr == 2){
                                $score = '<b style="color:#c0c0c0;">'.$row['Score'].'</b>';
                            } elseif($ctr == 3){
                                $score = '<b style="color:#8b4513;">'.$row['Score'].'</b>';
                            } else {
                                $score = '<b>'.$row['Score'].'</b>';
                            }

                            echo '<tr>
                            <td align="center">'.$ctr.'</td>
                            <td>';
                            ?>
                            <ul id="menu" style="padding-left: 0px;margin-bottom: 0px;padding-bottom: 0px;">
                            <li>
                                <?=((!$getBadge) ? '':''.GenerateBadge1(bin2hex($getBadge[0]['GuMarkImage'])).'')?>
                            </li>
                            <li>
                                <?=((!$getBadge) ? '':''.$getBadge[0]['GuName'].'')?>
                            </li>
                            </ul>
                            <?php
                            echo '</td>
                            <td align="center">'.$score.'</td>
                            </tr>';
                        }
                    ?>
                </tbody>
                </table>



                 <form method="POST" action="<?=base_url()?>adminpanel/UpdateConquerorPoints" data-parsley-validate>
                    <div class="pd-30 pd-sm-40 bg-white-200 bd bd-1">

                        <div id="replace">

                        <div class="form-group">
                          <label class="form-label">Date:</label>
                          <input type="text" class="form-control wd-250" placeholder="Date" required readonly>
                        </div><!-- form-group -->


                        <div class="form-group">
                          <label class="form-label">Points:</label>
                          <input type="text" name="Points" class="form-control wd-250" placeholder="Points" required>
                        </div><!-- form-group -->

                        </div>

                        
                        

                        <br />
                      <button type="submit" class="btn btn-info pd-x-20 ">Update</button>
                    </div>
                  </form>
                </div>
                
                <div class="col-md-8">
                    <table class="table table-bordered" style="font-size:12px;">
                    
                    <?php
                    echo '<tr>';
                    $i = 1;
                    foreach (array_chunk($CPData, 7, true) as $array) {
                        foreach($array as $item) {
                            $getBadge = $this->llllllllz_model->getGuild($item['GuNum']);


                            


                            echo '<td style="vertical-align:middle; text-align:center; cursor:pointer;" onclick="GetDate('.$item['CPID'].')">';
                            echo formatdate($item['CPDate']);
                            ?>
                            <ul id="menu" style="padding-left: 0px;margin-bottom: 0px;padding-bottom: 0px;">
                            <li data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=((!$getBadge) ? 'No Guild':''.$getBadge[0]['GuName'].'')?>">
                                <?=((!$getBadge) ? '':''.GenerateBadge1(bin2hex($getBadge[0]['GuMarkImage'])).'')?>
                            </li>
                            <li>
                            </li>
                            </ul>
                            <?php
                            
                            echo $item['CPPoints'].' <br />
                            
                            </td>';

                            if($i > 0 && $i % 7 == 0) {
                                
                                echo '</tr>';
                            }
                            
                            $i++;
                        }
                        
                    }
                     echo '</tr>';
                    ?>
                    </table>                
                </div>

            <?php
            }
            ?>
              
            </div>
        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 

<script>
    function GetDate(e){
        $.ajax({
        type:'POST',
        url:'<?php echo base_url(); ?>adminpanel/GetConquerorPathDate/',
        data: {e: e},
            success:function(data){
        $('#replace').html(data);
        }
        });
    }
</script>


  <script>
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