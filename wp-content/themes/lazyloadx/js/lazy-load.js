jQuery(document).ready(function($) {
    $('.tab-links a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');

        // Show the selected tab
        $('.tab').removeClass('active').hide();
        $(target).addClass('active').show();

        // Handle Lazy Loading Dynamically
        var lazyLoadElement = $(target).find('.lazy-load');
        if (lazyLoadElement.length && !lazyLoadElement.data('loaded')) {
            var action = lazyLoadElement.data('action'); // Get action dynamically
            var postId = lazyLoadElement.data('post-id'); // Get post ID dynamically

            if (action) {
                loadTabContent(lazyLoadElement, action, postId);
            }
        }
    });

    function loadTabContent(element, action, postId) {
        element.html('<p>Loading...</p>');

        $.ajax({
            type: "GET", // Changed to GET as suggested
            url: frontend_ajax_object.ajaxurl,
            data: {
                action: "load_tab_content", // Unified AJAX handler
                security: frontend_ajax_object.security,
                tab_action: action,
                post_id: postId
            },
            success: function(response) {
                element.html(response);
                element.data('loaded', true);
            },
            error: function() {
                element.html('<p>Error loading content.</p>');
            }
        });
    }

    $('.tab').hide();
    $('#tab1').show();
    
    var footerLoaded = false;

    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        var documentHeight = $(document).height();

        if (!footerLoaded && scrollTop + windowHeight >= documentHeight - 100) {
            footerLoaded = true;

            $.ajax({
                type: "GET",  // Changed from POST to GET
                url: frontend_ajax_object.ajaxurl,
                data: {
                    action: "load_lazy_footer",
                    security: frontend_ajax_object.security,
                },
                success: function (response) {
                    if (response.success) {
                        $('#footer-container').html(response.data);
                    } else {
                        $('#footer-container').html('<p>Error loading footer.</p>');
                    }
                },
                error: function () {
                    $('#footer-container').html('<p>Error loading footer.</p>');
                }
            });
        }
    });
    
});
