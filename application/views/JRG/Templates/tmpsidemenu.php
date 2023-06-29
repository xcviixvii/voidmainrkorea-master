<div class="az-content-left az-content-left-mail">
<a id="btnCompose" href="<?=base_url()?>adminpanel/createnews" class="btn btn-az-primary btn-compose">Add
        User Type</a>

<div class="az-mail-menu">
    <nav class="nav az-nav-column mg-b-20">
        <a href="<?=base_url()?>adminpanel/news" class="nav-link <?=(($this->uri->segment(2) == "news") ? "active":"")?>">
            News List 
        </a>
        <a href="<?=base_url()?>adminpanel/draft" class="nav-link <?=(($this->uri->segment(2) == "draft") ? "active":"")?>">
            Drafts 
        </a>
        <a href="" class="nav-link">Trash</a>
    </nav>
</div>

</div><!-- az-content-left -->