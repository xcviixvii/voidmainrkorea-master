<div class="az-content-header d-block d-md-flex">
    <div class="az-content-breadcrumb">
        <span>Ran Panel</span>
        <?php 
            $ParentModule = $this->Internal_model->ParentModule($this->uri->segment(2));
            if($ParentModule != $this->uri->segment(2)){
                $ParentMod = '<span>'.$ParentModule.'</span>';
                echo $ParentMod;
            } 
        ?>
        <span><?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></span>
        <?php
            if($this->uri->segment(3)) {
                if($this->uri->segment(2) == 'UsersPermission'){
                    echo '<span>'.$this->Internal_model->GetUserTypeDescByID($this->uri->segment(3)).'</span>';
                } else {
                    echo '<span>'.$this->uri->segment(3).'</span>';            
                }
            }
        ?>
    </div>
</div>