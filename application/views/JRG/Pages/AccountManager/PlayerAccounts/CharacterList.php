<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>
<style>
  ul#menu li {
    display: inline-table;
  }
</style>

<div class="az-content-body">

<div class="az-dashboard-header-right" style="float:right;">
  <table>
              <tr>
              <td>
              <form method='post' action="<?=base_url()?>adminpanel/CharacterList" >
              <div class="input-group">
                  <input type="text" class="form-control" name="srchcharacter" placeholder="">
                  <span class="input-group-btn">
                    <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search"></i></button>
                  </span>
                </div><!-- input-group -->
               </form>
              </td>
             
                <td><a href="<?=base_url()?>adminpanel/clearsession/CharacterList" class="btn btn-outline-success"><i class="fa fa-undo"></i></a></td>
              </tr>
  </table>  
</div>
<br /><br /><br />
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
   
             <table id="example2" class="table table-bordered table-hover" style="font-size: 12px;">
                <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="20%">Character</th>
                        <th width="5%">Level</th>
                        <th width="10%">Class</th>
                        <th width="5%">School</th>
                        <th width="10%">Club</th>
                        <th width="10%">Kill / Death</th>
                        <th width="10%">Gold</th>
                      </tr>
                    </thead>
               <?php

                      $ctr = 1;
                      foreach ($records as $row) {
                        $getBadge = $this->llllllllz_model->getGuild($row['GuNum']);
                        echo '<tr class="clickable-row" data-href="'.base_url().'adminpanel/CharacterList/CharacterInformation/'.$row['ChaNum'].'" style="cursor: pointer;">
                        <td>'.$ctr.'</td>
                        <td>'.(($row['ChaOnline'] == 1) ? "<small><i class='fa fa-circle text-success'></small></i>":"<small><i class='fa fa-circle' style='color:#d2d6de;'></small></i>").' '.$row['ChaName'].'</td>
                        <td align="center">'.$row['ChaLevel'].'</td>
                        <td>'.ChaClass($row['ChaClass']).'</td>
                        <td align="center">'.school($row['ChaSchool']).'</td>
                        <td>';
                        ?>
                        <?php  $getBadge = $this->llllllllz_model->getGuild($row['GuNum']); ?>
                        <ul id="menu" style="padding-left: 0px;margin-bottom: 0px;padding-bottom: 0px;">
                          <li>
                            <?=((!$getBadge) ? '':''.GenerateBadge1(bin2hex($getBadge[0]['GuMarkImage'])).'')?>
                          </li>
                          <li>
                            <?=((!$getBadge) ? 'No Guild':''.$getBadge[0]['GuName'].'')?>
                          </li>
                        </ul>
                        </div>
                        <?php echo '
                        </td>
                        <td>'.$row['ChaPkWin'].' / '.$row['ChaPkLoss'].'</td>
                        <td align="right">'.formatnumber2($row['ChaMoney']).'</td>
                        ';
                      
                      $ctr++;
                      }
                      ?> 
               </tr></a>
              </table>
              <center><?=$pages?></center>
              

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 

<script>
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
}); 
</script>
