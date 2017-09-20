<?php

echo "<section class=\"section pb-3 text-center\">

    <div class=\"row\">";
while( $loop->have_posts() ) {
$loop->the_post();
?>
<!--Grid column-->
<div class="col-lg-4 col-md-12 mb-r">

    <!--Card-->
    <div class="card testimonial-card">

        <!--Background color-->
        <div class="card-up teal lighten-2">
        </div>

        <!--Avatar-->
        <div class="avatar">
            <?php
            if (has_post_thumbnail($post->ID)) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
                ?>
                <img src="<?php echo $image[0]; ?>" class="rounded-circle img-responsive">
            <?php } ?>
        </div>

        <div class="card-body">
            <!--Name-->
            <h4 class="card-title mt-1"><?php echo get_post_meta($post->ID, "_testimonials_author_name", true); ?></h4>
            <hr>
            <!--Quotation-->
            <p>
                <i class="fa fa-quote-left"></i> <?php echo get_post_meta($post->ID, "_testimonials_description", true); ?>
            </p>
        </div>

    </div>
    <!--Card--> </div>
    <?php
    }
            ?>


    </div>

</section>