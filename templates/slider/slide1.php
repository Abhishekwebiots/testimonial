<section class="regular wi-slider">
    <?php

    while( $loop->have_posts() ) {
        $loop->the_post();
        ?>
        <div class="wi-slide">
            <div class="wi-testimonial">
                <div class="wi-user-img">
                    <?php
                    if (has_post_thumbnail( $post->ID ) ) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
                    ?>
                    <img src="<?php echo $image[0]; ?>" class="center-block rounded-circle z-depth-1 img-fluid">

                    <?php
                    }else{ ?>
                        <img src="http://lorempixel.com/250/250" class="center-block rounded-circle z-depth-1 img-fluid">
                    <?php }
                         ?>
                </div>

                <!--Content-->
                <h3 class="wi-mb-3"><?php echo get_post_meta($post->ID,"_testimonials_author_name", true); ?></h3>
                <h6 class="wi-mb-3 wi-font-bold wi-grey-text"><?php echo get_post_meta($post->ID, "_testimonials_designation", true); ?></h6>
                <div class="wi-social-icons">
                    <ul class="list-inline">
                        <?php
                        $fburl = get_post_meta($post->ID, "_testimonials_fb_url", true);
                        if(strlen($fburl)>0){
                            ?>
                            <li><a href="<?php echo $fburl; ?>" target="_blank"> <i class="fa fa-facebook" aria-hidden="true" ></i></a></li>
                        <?php
                        }
                        $linkedurl = get_post_meta($post->ID, "_testimonials_linkedin_url", true);
                        if(strlen($linkedurl)>0){
                            ?>
                            <li><a href="<?php echo $linkedurl; ?>" target="_blank"> <i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <?php
                        }
                        $twitterurl = get_post_meta($post->ID, "_testimonials_twitter", true);
                        if(strlen($twitterurl)>0){
                            ?>
                            <li><a href="<?php echo $twitterurl; ?>" target="_blank"> <i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <p><i class="fa fa-quote-left"></i>  <?php echo get_post_meta($post->ID,"_testimonials_description", true); ?></p>

                <!--Review-->
                <div class="wi-orange-text">
                    <?php $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);
                    if($value_testimonials_rate>0){
                        for($i=0;$i<=$value_testimonials_rate;$i++){
                            echo "<i class=\"fa fa-star\"></i>";
                        } }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

</section>
