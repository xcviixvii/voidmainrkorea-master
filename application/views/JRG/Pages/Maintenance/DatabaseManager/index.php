<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<?php
        $CI = &get_instance();
        $this->db2 = $this->load->database('db2', TRUE); // RanGame1
        $this->db3 = $this->load->database('db3', TRUE); // RanShop
        $this->db4 = $this->load->database('db4', TRUE); // RanUser

        if($this->uri->segment(3) == "RanGame1"){
            $tables = $this->db2->list_tables();
        }elseif($this->uri->segment(3) == "RanShop"){
            $tables = $this->db3->list_tables();
        }elseif($this->uri->segment(3) == "RanUser"){
            $tables = $this->db4->list_tables();
        }
?>
<div class="az-content-body">
    <div class="container maxheight" >
        <div class="az-content-left az-content-left-mail">
        <div class="az-mail-menu">
            <div class="alert alert-outline-info" role="alert">
                <a href="<?=base_url()?>adminpanel/DatabaseManager/RanGame1"><strong>RanGame1</strong></a> 
                
                <br />
                <a href="<?=base_url()?>adminpanel/DatabaseManager/RanShop"><strong>RanShop</strong></a>
                <br />
                <a href="<?=base_url()?>adminpanel/DatabaseManager/RanUser"><strong>RanUser</strong></a>
            </div>
        </div>
        </div>


        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
            <div class="table-responsive">
                <?php
                if($this->uri->segment(3) != ""){
                    ?>
                    <a href="<?=base_url()?>adminpanel/truncatedatabase/<?=$this->uri->segment(3);?>" style="float:right;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Truncate All Table From <?=$this->uri->segment(3);?>"><i class="fas fa-biohazard"></i> Click Me </a>
                    <?php
                }
                ?>
                
                <table class="table az-table-reference mg-t-0">
                    <thead>
                        <tr>
                            <th>Table Name </th>
                            <th>Data Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($this->uri->segment(3) != ""){
                            foreach ($tables as $row) {
                                if($this->uri->segment(3) == "RanGame1"){
                                    $data = $this->db2->count_all($row);
                                }elseif($this->uri->segment(3) == "RanShop"){
                                    $data = $this->db3->count_all($row);
                                }elseif($this->uri->segment(3) == "RanUser"){
                                    $data = $this->db4->count_all($row);
                                }
                                

                                echo '
                                    <tr>
                                        <td>'.$row.'</td>
                                        <td>'.$data.'</td>
                                        <td><a href="'.base_url().'adminpanel/truncatetable/'.$this->uri->segment(3).'/'.$row.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="Truncate '.$row.'"><i class="fas fa-recycle"></i></a></td>
                                    </tr>';
                            }
                        } else {
                             echo '
                                <tr>
                                    <td colspan="999" align="center">No Record Found ...</td>
                                </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- az-content-body --> 


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