<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>
<style>
 
</style>
<div class="az-content-body">
  <div class="container maxheight" >
    <div class="az-content-left az-content-left-mail">
    <div class="az-mail-menu">
        <div class="alert alert-outline-info" role="alert">
            <?php
            $UserTypeID = "";
                $Detail = $this->Internal_model->getUserTypeByID($this->uri->segment(3));
                foreach ($Detail as $key) {
                    $UserTypeID .= $key['UserTypeID'];
                    echo '<strong>ID :</strong> '.$key['UserTypeID'].'
                    <br />
                    <strong>Type :</strong> '.$key['UserTypeDesc'].'';
                }

            ?>
            
            <br />
          </div>
    </div>
    </div>
    <div class="az-content-body pd-lg-l-40 d-flex flex-column">
      <div class="table-responsive">
        <table class="table az-table-reference mg-t-0">
          <thead>
            <tr>
                <th width="5%">ID </th>
                <th width="45%">Module Name</th>
                <th width="5%">Show</th>
                
            </tr>
          </thead>
          <tbody>
            <?php
                $ctr = 1;
                foreach ($Module as $row) {
                    echo '
                        <tr>
                        <td>'.$row['SeqNo'].'</td>
                        <td onclick="toggle'.$row['ID'].'()"> '.$row['ModuleDesc'].' '.(($row['HasChild'] == 1) ? '<a  style=""><i class="fas fa-chevron-down" style="font-size:8px;color: #cdd4e0;cursor:pointer;"></i><a/> ':"").'
                        </td>
                        <td class="text-center">
                        '.(($row['HasChild'] == 1) ? '
                        <label class="ckbox">
                            <input type="checkbox" '.(($this->Internal_model->GetShowModule($row['ID'],$UserTypeID) == 1) ? "Checked":"").' disabled><span></span> 
                        </label>
                        ':'
                        <label class="ckbox">
                            <input type="checkbox" class="enable'.$row['ID'].'" id="'.$row['ID'].'" '.(($this->Internal_model->GetShowModule($row['ID'],$UserTypeID) == 1) ? "Checked":"").'><span></span> 
                        </label>').'
                                
                        </td>
                        </tr>
                        ';
                        ?>
                        <script>
                            $('.enable<?=$row['ID']?>').click(function () {
                                let ModuleID = $(this).attr('id');
                                let UserType = <?=$UserTypeID;?>;
                                    $.ajax({
                                        type: 'GET',
                                        dataType: 'json',
                                        contentType: "application/json; charset=utf-8",
                                        url: '<?=base_url()?>adminpanel/ModulePermission',
                                        data: {
                                            ModuleID: ModuleID, UserType: UserType
                                        }
                                    });
                            });
                        </script>
                        <?php

                        if($row['HasChild'] == 1){
                        echo '
                        <tr style="display: none;" id="hidethis'.$row['ID'].'">
                            <td colspan="99">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="45%">Module Name</th>
                                        <th width="5%">Show</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                
                                $SubModule = $this->Internal_model->getAllSubModuleByUserType($row['ParentModule']);
                                    $subCtr = 1;
                                    foreach ($SubModule as $sub) {
                                        echo '
                                        <tr>
                                        <td>'.$sub['SubSeqNo'].'</td>
                                        <td>'.$sub['ModuleDesc'].'</td>
                                        <td>
                                            <label class="ckbox">
                                                <input type="checkbox" class="enable'.$sub['ID'].'" id="'.$sub['ID'].'" '.(($this->Internal_model->GetShowModule($sub['ID'],$UserTypeID) == 1) ? "Checked":"").'><span></span> 
                                            </label>
                                        </td>
                                        </tr>';
                                         ?>
                                        <script>
                                            $('.enable<?=$sub['ID']?>').click(function () {
                                                let ModuleID = $(this).attr('id');
                                                let UserType = <?=$UserTypeID;?>;
                                                    $.ajax({
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        contentType: "application/json; charset=utf-8",
                                                        url: '<?=base_url()?>adminpanel/ModulePermission',
                                                        data: {
                                                            ModuleID: ModuleID, UserType: UserType
                                                        }
                                                    });
                                            });
                                        </script>
                                        <?php
                                        $subCtr++;
                                    }
                                echo '</tbody></table>
                            </td>
                        </tr>
                        ';
                        ?>
                        <script>
                            function toggle<?=$row['ID']?>() {
                                if (document.getElementById("hidethis<?=$row['ID']?>").style.display == 'none') {
                                    document.getElementById("hidethis<?=$row['ID']?>").style.display = '';
                                } else {
                                    document.getElementById("hidethis<?=$row['ID']?>").style.display = 'none';
                                }
                            }
                        </script>
                        <?php
                    }
                    $ctr++;
                }
            ?>
          </tbody>
        </table>
      </div><!-- table-responsive -->

    </div><!-- az-content-body -->
  </div><!-- container -->
</div>

