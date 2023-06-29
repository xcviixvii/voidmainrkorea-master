<div class="az-content-left az-content-left-mail">
          <a id="btnCompose" href="<?=base_url()?>adminpanel/News/CreateNews" class="btn btn-az-primary btn-compose">Create News</a>
          <div class="az-mail-menu">
            <nav class="nav az-nav-column mg-b-20">
              <a href="<?=base_url()?>adminpanel/News" class="nav-link <?=(($this->uri->segment(2) == "News") ? "active":"")?>"> News List <span><?=$this->llllllllz_model->getCountNewsList();?></span></a>
              <a href="<?=base_url()?>adminpanel/News/Draft" class="nav-link <?=(($this->uri->segment(2) == "Draft") ? "active":"")?>"> Drafts <span><?=$this->llllllllz_model->getCountNewsListDraft();?></span></a>
              <a href="" class="nav-link"> Trash <span>0</span></a>
            </nav>
          </div>

        </div><!-- az-content-left -->