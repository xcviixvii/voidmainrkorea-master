
<div class="az-sidebar">
      <div class="az-sidebar-header">
        <a href="<?=base_url()?>adminpanel/dashboard" class="az-logo">az<span>i</span>a</a>
      </div><!-- az-sidebar-header -->
      <div class="az-sidebar-loggedin">
        <div class="az-img-user online"><img src="<?=base_url()?>Library/img/voidmain.png" alt=""></div>
        <div class="media-body">
          <h6><?=$this->session->userdata('UserName')?></h6>
            <span <?=cShadow($this->session->userdata('UserType'))?>>
                <?=$this->session->userdata('UserType'); ?>
            </span>
        </div><!-- media-body -->
      </div><!-- az-sidebar-loggedin -->
      <div class="az-sidebar-body">
        <ul class="nav">
          <?php
          $Mod = $this->Internal_model->getAllModule($this->session->userdata('UserTypeID'));
          $ParentModule = $this->Internal_model->ParentModule($this->uri->segment(2));
          foreach ($Mod as $row) {
            ?>
            <li class="nav-item <?=(($ParentModule == $row['ParentModule']) ? "active show":"")?>">
              <a href="<?=base_url()?>adminpanel/<?=$row['ModuleName']?>" class="nav-link <?=(($row['HasChild'] == 1) ? "with-sub":"")?>">
              <i class="<?=$row['ModuleIcon']?>"></i><?=$row['ModuleDesc']?>
              </a>
            <?php
              if($row['HasChild'] == 1){
                $SubMod = $this->Internal_model->getSubModule($row['ParentModule'],$this->session->userdata('UserTypeID'));
                ?>
                <ul class="nav-sub">
                  <?php
                    foreach ($SubMod as $key) {
                      echo '<li class="nav-sub-item '.(($this->uri->segment(2) == $key['ModuleName']) ? "active":"").'"><a href="'.base_url().'adminpanel/'.$key['ModuleName'].'" class="nav-sub-link">'.$key['ModuleDesc'].'</a></li>';
                    }
                  ?>
                </ul>
                <?php
              }
            ?>
            </li><!-- nav-item -->
            
            <?php
          }
          ?>
          <!-- <li class="nav-item"  >
            <a class="nav-link"><i class="typcn icon typcn-key-outline" style="color:#00fa9a;"></i>Premium</a>
          </li> -->
       
        </ul><!-- nav -->
      </div><!-- az-sidebar-body -->
    </div><!-- az-sidebar -->