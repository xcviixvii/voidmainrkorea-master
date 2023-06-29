<section class="server-info">
  <table>
    <caption class="display-block">
      <figure>
        <div class="title">
          <div class="left"><img src="<?=base_url()?>Images/ServerInfo/serverinfo_title.gif" alt="Server Info"></div>
          <div class="clear"></div>
        </div>
      </figure>
    </caption>   
    <thead>
      <tr class="blankline2">
        <td class="blankcell" colspan="13"></td>
      </tr>
      <tr>
        <td>Server Status</td>
        <td>
        <?=(($this->Internal_model->GetServerStatus() == 1) ? "<b style='color:green;'>Online</b>":"<b  style='color:red;'>Offline</b>")?>
        </td>
      </tr>
      <tr class="blankline2">
        <td class="blankcell" colspan="13"></td>
      </tr>
      </thead>
      
     
      <tbody class="server-info-list">
        <tr>
          <td width="50%">Player Population:</td>
          <td width="50%"><label title="<?=$this->Internal_model->GetPlayerOnline()?>">
          <?php
          $Population = $this->Internal_model->GetPlayerOnline();
          //$this->Internal_model->GetPlayerOnline()
          if($Population >= 300){
            echo '<b style="color: red;">Busy</b>';
          } elseif($Population >= 100){
            echo '<b style="color: orange;">Normal</b>';
          } else {
            echo '<b style="color: green;">Smooth</b>';
          }
          ?>
          </label></td>
        </tr>
        
        <!-- <tr>
          <td>GM Online:</td>
          <td><label style="color:green;"><b><?=$this->Internal_model->GetGMOnline();?></b></label></td>
        </tr> -->
        
        <?php
        $ServerInfo = $this->Internal_model->GetpServerInformation();
        foreach ($ServerInfo as $row) {
          echo '
          <tr>
            <td>'.$row['Name'].'</td>
            <td>';
            if($row['Category'] == 1) {
              echo  $row['Detail'];
            } else {
              ?>
              <div id="BattleTime<?=$row['ServerInfoID']?>" style="font-size: 9px; font-weight: bold;"></div>
              <?php
            }
          echo '</td>
          </tr>';
          ?>
          <script type="text/javascript">
            $('#BattleTime<?=$row['ServerInfoID']?>').load('<?=base_url()?>homepage/GetBattleTime/<?=$row['ServerInfoID']?>');

              setInterval(function(){
                    $('#BattleTime<?=$row['ServerInfoID']?>').load('<?=base_url()?>homepage/GetBattleTime/<?=$row['ServerInfoID']?>');
                },5000);
          </script>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr class="blankline2">
          <td class="blankcell" colspan="13"></td>
        </tr>
        
      </tfoot>                  
  </table>
</section>

