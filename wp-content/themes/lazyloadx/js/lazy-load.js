jQuery(document).ready(function($) {
    $('.tab-links a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        $('.tab').removeClass('active').hide(); 
        $(target).addClass('active').show(); 
        $('.lazy-load').each(function() {
            $(this).data('loaded', false); 
            $(this).empty();
            $(this).html('<p>Loading...</p>'); 
        });
        if ($(target).is('#tab2')) {
            var lazyLoadElement = $(target).find('.lazy-load');
            if (!lazyLoadElement.data('loaded')) {
                var postId = lazyLoadElement.data('post-id'); 
                $.ajax({
                    url: frontend_ajax_object.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'load_youtube_playlist',
                        post_id: postId
                    },
                    success: function(response) {
                        lazyLoadElement.html(response);
                        lazyLoadElement.data('loaded', true);
                    },
                    error: function() {
                        lazyLoadElement.html('<p>Error loading YouTube playlist.</p>');
                    }
                });
            }
        }
        if ($(target).is('#tab3')) {
            var lazyLoadElement = $(target).find('.lazy-load');
            if (!lazyLoadElement.data('loaded')) {
                var postId = lazyLoadElement.data('post-id'); 
                $.ajax({
                    url: frontend_ajax_object.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'load_featured_image',
                        post_id: postId
                    },
                    success: function(response) {
                        lazyLoadElement.html(response);
                        lazyLoadElement.data('loaded', true);
                    },
                    error: function() {
                        lazyLoadElement.html('<p>Error loading featured image.</p>');
                    }
                });
            }
        }
    });
    
    $('.tab').hide();
    $('#tab1').show(); 
    var footerLoaded = false;
    var footerContent = ''; 
    $(window).scroll(function() {
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        var documentHeight = $(document).height();
        if (!footerLoaded && scrollTop + windowHeight >= documentHeight - 100) {
            footerLoaded = true; 

            $.ajax({
                url: frontend_ajax_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_lazy_footer',
                },
                success: function(response) {
                    if (response.success) {
                        footerContent = response.data; 
                        $('#footer-container').append(footerContent);
                    }
                },
                error: function() {
                    console.log('Error loading footer');
                }
            });
        }
        if (footerLoaded && scrollTop + windowHeight < documentHeight - 100) {
            $('#footer-container').empty(); 
            footerLoaded = false;
        }
    });
});