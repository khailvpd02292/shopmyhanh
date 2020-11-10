
jQuery(document).ready(function($){
    $(window).on('resize', function()
    {
        initFixProductBorder();
        initIsotopeFiltering();
        initFixProductBorder();
        initSlider();
        initThumbnail();
        initSliderUpToBotom();
        initSliderRelated();
        searchDialog();
    });
    //fix init
    initFixProductBorder();
    initIsotopeFiltering();
    initFixProductBorder();
    initSlider();
    initThumbnail();
    initSliderUpToBotom();
    initSliderRelated();
    searchDialog();
    activeMenu();
    
    function initIsotopeFiltering() {
        if($('.shop_sorting_button').length)
        {
            $('.shop_sorting_button').click(function()
            {
                // putting border fix inside of setTimeout because of the transition duration
                setTimeout(function()
                {
                    initFixProductBorder();
                },500);

                $('.shop_sorting_button.active').removeClass('active');
                $(this).addClass('active');
         
                var selector = $(this).attr('data-filter');
                $('.shop-filter-products').isotope({
                    filter: selector,
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false
                    }
                });

                
                 return false;
            });
        }
    }

    function initFixProductBorder(){
        if($('.product-inner').length)
        {
            var products = $('.product-inner:visible');
            var wdth = window.innerWidth;

            // reset border
            products.each(function()
            {
                $(this).css('border-right', 'solid 1px #e9e9e9');
            });

            // if window width is 991px or less

            if(wdth < 480)
            {
                for(var i = 0; i < products.length; i++)
                {
                    var product = $(products[i]);
                    product.css('border-right', 'none');
                }
            }

            else if(wdth < 576)
            {
                if(products.length < 5)
                {
                    var product = $(products[products.length - 1]);
                    product.css('border-right', 'none');
                }
                for(var i = 1; i < products.length; i+=2)
                {
                    var product = $(products[i]);
                    product.css('border-right', 'none');
                }
            }

            else if(wdth < 768)
            {
                if(products.length < 5)
                {
                    var product = $(products[products.length - 1]);
                    product.css('border-right', 'none');
                }
                for(var i = 2; i < products.length; i+=3)
                {
                    var product = $(products[i]);
                    product.css('border-right', 'none');
                }
            }

            else if(wdth < 992)
            {
                if(products.length < 5)
                {
                    var product = $(products[products.length - 1]);
                    product.css('border-right', 'none');
                }
                for(var i = 3; i < products.length; i+=4)
                {
                    var product = $(products[i]);
                    product.css('border-right', 'none');
                }
            }

            //if window width is larger than 991px
            else
            {
                if(products.length < 5)
                {
                    var product = $(products[products.length - 1]);
                    product.css('border-right', 'none');
                }
                for(var i = 4; i < products.length; i+=5)
                {
                    var product = $(products[i]);
                    product.css('border-right', 'none');
                }
            }   
        }
    }

    function initSlider(){

        if($('.shop-filter-products.slider').length)
        {

            var slider = $('.shop-filter-products.slider');

            slider.owlCarousel({
                loop:false,
                dots:false,
                nav:false,
                responsive:
                {
                    0:{items:1},
                    480:{items:2},
                    768:{items:3},
                    991:{items:5},
                    1280:{items:5},
                    1440:{items:5}
                }
            });

            if($('.product_slider_nav_left').length)
            {
                $('.product_slider_nav_left').on('click', function()
                {
                    slider.trigger('prev.owl.carousel');
                });
            }

            if($('.product_slider_nav_right').length)
            {
                $('.product_slider_nav_right').on('click', function()
                {
                    slider.trigger('next.owl.carousel');
                });
            }
        }
    }

    if( $('.shop-slider-product .owl-carousel .main_items').length ){
        
        $('.shop-slider-product .owl-carousel .main_items').hover(function(){
           $(this).addClass('main_items_active');
        }, function(){
            $(this).removeClass('main_items_active');
        });
    }

    function initThumbnail(){
        if($('.shop_single_product_thumbnails div').length)
        {
            var thumbs = $('.shop_single_product_thumbnails div');
            // var singleImage = $('.shop_single_product_image_background');

            thumbs.each(function()
            {

                var item = $(this);
                item.find('a').removeAttr("href").attr("href");
                $(document).on('click', '.woocommerce-product-gallery__image' , function(e){
                    e.preventDefault(); 
                    var photo_fullsize =  $(this).find('img').attr('src');

                    $('.shop_single_product_image img').attr('src', photo_fullsize);

                });
            });            

        }  

    }


    function initSliderUpToBotom() {
        if($(".shop_single_product_thumbnails.owl-carousel").length){
            $(".shop_single_product_thumbnails.owl-carousel").owlCarousel({
                items: 4,
                loop: true,
                autoplay: true,
                margin:10,
                nav: true,
                dots: true,
                navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"]
            });
        }

    }

    function initSliderRelated() {
        if($('.product-loop-relate.owl-carousel').length){
            var slider = $(".product-loop-relate.owl-carousel");
            $(".product-loop-relate.owl-carousel").owlCarousel({
                items: 4,
                loop: true,
                autoplay: true,
                margin:10,
                dots:false,
                nav:false
            });

            if($('.related.products .controls').length)
                {
                    $('.left-arrow').on('click', function()
                    {
                        slider.trigger('prev.owl.carousel');

                    });

                }

            if($('.related.products .controls').length)
                {
                    $('.right-arrow').on('click', function()
                    {
                        slider.trigger('next.owl.carousel');
                    });
                }
        }
        
    }

    function searchDialog(){
        if( $('#shop_search_popup_wrapper').length){
            var searchDialog = $('#shop_search_popup_wrapper');
            function openDialog(){
                searchDialog.addClass('dialog--open');
            }

            function closeDialog(){
                searchDialog.removeClass('dialog--open');
            }

            $('li.shop-search a').click(function(){
                openDialog();
            });

            $("#shop_search_popup_wrapper .search-close-popup").click(function(){
                closeDialog();
            });  
        }  

    }

    function activeMenu(){
        if( $('.main_nav_container ul.navbar_user li .shop-bars').length){

            $('.main_nav_container ul.navbar_user li .shop-bars').click(function(){
                $('.shop_bars_menu').addClass('active');
            });

            $('.shop_bars_close').click(function(){
                $('.shop_bars_menu').removeClass('active');
            });
        }
    }
});