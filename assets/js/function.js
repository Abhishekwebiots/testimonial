    jQuery(document).on('ready', function() {
        jQuery(".regular").slick({
            fade: true,
            cssEase: 'linear'
        });
        jQuery(".single").slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
        });
    });