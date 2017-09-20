<?php

echo "<section>
    <div class=\"slider-content\">
        <div class=\"container\">
            <div class='row'>
                <div class='col-md-12'>
                    <div class=\"carousel slide\" data-ride=\"carousel\" id=\"quote-carousel\">

                    <!-- Carousel Slides / Quotes -->
                    <div class=\"carousel-inner\">";

                        while( $loop->have_posts() ) {
                        $loop->the_post();
                        ?>
                        <div class="item">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo get_post_meta($post->ID,"_testimonials_description", true); ?>
                                    <br>
                                    <div class="line"></div>
                                    <div>
                                        <ul class="details_client">
                                            <li><?php echo get_post_meta($post->ID,"_testimonials_author_name", true); ?></li>
                                            <li><?php echo get_post_meta($post->ID, "_testimonials_designation", true); ?></li>
                                            <li><?php echo get_post_meta($post->ID, "_testimonials_profile_url", true); ?></li>
                                            <li>
                                                <?php $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);
                                                if($value_testimonials_rate>0){
                                                    for($i=0;$i<=$value_testimonials_rate;$i++){
                                                        echo "<i class=\"fa fa-star\"></i>";
                                                    } }
                                                ?>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php

                                    if(get_post_meta($post->ID, "_testimonials_youtube", true)){
                                        ?>
                                        <iframe width="554" height="376" src="https://www.youtube.com/embed/<?php echo get_post_meta($post->ID, "_testimonials_youtube", true); ?>" frameborder="0" allowfullscreen></iframe>
                                        <?php

                                    }else{
                                        if (has_post_thumbnail( $post->ID ) ) {
                                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
                                            ?>
                                            <img src="<?php echo $image[0]; ?>">

                                            <?php
                                        }

                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
<?php

}
echo "</div>  <!-- Carousel Buttons Next/Prev -->
                            <a data-slide=\"prev\" href=\"#quote-carousel\" class=\"left carousel-control\"><i class=\"fa fa-chevron-left arrow-t prev-t\"></i></a>
                            <a data-slide=\"next\" href=\"#quote-carousel\" class=\"right carousel-control\"><i class=\"fa fa-chevron-right arrow-t next-t\"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>";