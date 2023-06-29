<div class="az-content-left az-content-left-mail">
	<a id="btnCompose" href="<?=base_url()?>adminpanel/ItemShop/AddItem" class="btn btn-az-primary btn-compose">Add Item</a>
          <div class="az-mail-menu">
            <nav class="nav az-nav-column mg-b-20">
            	<a href="<?=base_url()?>adminpanel/ItemShop" class="nav-link <?=(($this->uri->segment(3) == "") ? "active":"")?>"></i> Item List </a>
            	<a href="<?=base_url()?>adminpanel/ItemShop/Configuration" class="nav-link <?=(($this->uri->segment(3) == "Configuration") ? "active":"")?>"></i> Configuration </a>
            	<a href="<?=base_url()?>adminpanel/ItemShop/Settings" class="nav-link <?=(($this->uri->segment(3) == "Settings") ? "active":"")?>"></i> Settings </a>
            </nav>
          </div>

        </div><!-- az-content-left -->