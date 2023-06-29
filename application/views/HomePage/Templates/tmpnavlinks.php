<nav class="<?=navclass($this->uri->segment(1))?>">
    <div>
    <a id="logo" href="<?=base_url()?>">Ran Online</a>
        <ul id="navigation-main">
            <li id="n-news" class="<?=(($this->uri->segment(1) == 'News') ? "active":"")?>"><a href="<?=base_url()?>News/Notice">News</a></li>
            <li id="n-community" class="<?=(($this->uri->segment(1) == 'Community') ? "active":"")?>"><a href="<?=base_url()?>Community/ScreenShot">community</a></li> <!-- <?=base_url()?>Community/ScreenShot -->
            <li id="n-shop" class="<?=(($this->uri->segment(1) == 'ItemShop') ? "active":"")?>"><a href="<?=base_url()?>ItemShop">Item Shop</a></li>
            <li id="n-ranking" class="<?=(($this->uri->segment(1) == 'Ranking') ? "active":"")?>"><a href="<?=base_url()?>Ranking">Ranking</a></li>
            <li id="n-Market" class="<?=(($this->uri->segment(1) == 'MarketPlace') ? "active":"")?>"><a href="<?=base_url()?>MarketPlace/Gold">Gold Market</a></li>
            <li id="n-download" class="<?=(($this->uri->segment(1) == 'Download') ? "active":"")?>"><a href="<?=base_url()?>Download">Download</a></li>
            <li id="n-support" class="<?=(($this->uri->segment(1) == 'Support') ? "active":"")?>"><a href="<?=base_url()?>Support">Support</a></li>
            <li id="n-donation" class="<?=(($this->uri->segment(1) == 'Donate') ? "active":"")?>"><a href="<?=base_url()?>Donate">Donation</a></li>
        </ul>
    </div>
</nav>