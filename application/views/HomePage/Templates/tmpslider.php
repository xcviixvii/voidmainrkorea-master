<section id="header-banner">
        <div class="panel">
            <div class="banner-container">
                <div class="wt-rotator">
                    <div class="screen"><noscript><img src="images/triworks_abstract17.jpg"/></noscript></div>
                    <div class="c-panel">
                        <div class="thumbnails">
                            <ul>
                                <?php
                                    foreach ($Slider as $row) {
                                        echo ' <li>
                                                    <a href="'.base_url().'Uploadfiles/Slider/'.$row['Image'].'" />
                                                    </a>
                                                    <a href="'.$row['Url'].'"></a>       
                                                </li>';
                                    }
                                ?>
                               
                            </ul>
                        </div>
                        <div class="buttons">
                            <div class="prev-btn"></div>
                            <div class="play-btn"></div>
                            <div class="next-btn"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


<script type="text/javascript">
    $(document).ready(function() {
            $('#navNavigation').addClass('active dropdown opened');
            $('#navNavigation ul').show();

            loadGlobalNavDropDown();

            var $panel = $(".panel");
            var $container = $panel.find(".banner-container");

            $container.wtRotator({
                width: 458,
                height: 208,
                thumb_width: 24,
                thumb_height: 24,
                button_width: 24,
                button_height: 24,
                button_margin: 5,
                auto_start: true,
                delay: 3000,
                transition: "fade.left",
                transition_speed: 800,
                block_size: 75,
                vert_size: 55,
                horz_size: 50,
                cpanel_align: "BR",
                timer_align: "top",
                display_thumbs: true,
                display_dbuttons: false,
                display_playbutton: false,
                display_thumbimg: false,
                display_side_buttons: false,
                tooltip_type: "text",
                display_numbers: true,
                display_timer: false,
                mouseover_select: false,
                mouseover_pause: true,
                cpanel_mouseover: false,
                text_mouseover: false,
                text_effect: "fade",
                text_sync: true,
                shuffle: false,
                block_delay: 25,
                vstripe_delay: 73,
                hstripe_delay: 183
            });
        });

        function loadGlobalNavDropDown() {
            var animationMilliseconds = 300,
                nav = $('#navigation'),
                drop = $('#drop'),
                main = $('#navigation-main li'),
                height = 0;
            /* show the drop and move it off screen */
            drop.show().css('left', -5000);
            /* get heights of the children and bind hover */
            drop.children('li').each(function(i) {
                var mainLI = main.eq(i);
                height = Math.max(height, $(this).height());
                $(this).bind('mouseenter', function() {
                    main.removeClass('hover');
                    mainLI.addClass('hover');
                });
                $(this).bind('mouseleave', function() {
                    main.removeClass('hover');
                });
            });
            /* set heights of the children */
            drop.children('li').height(height);
            /* now use the height var for the height of the drop */
            height = drop.height();
            drop.css({ 'height': 0, 'left': 0 });
            /* rollover state */
            nav.bind('mouseenter', function() {
                drop.stop().animate({ 'height': height }, animationMilliseconds);
            });
            nav.bind('mouseleave', function() {
                drop.stop().animate({ 'height': 0 }, animationMilliseconds);
            });
        }
</script>