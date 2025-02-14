jQuery(document).ready(function($) {
    $('.tab-links a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');

        // Hide all tabs and show the selected tab
        $('.tab').removeClass('active').hide(); // Hide all tabs
        $(target).addClass('active').show(); // Show the selected tab

        // Lazy load content for the second tab (YouTube Playlist)
        if ($(target).is('#tab2')) {
            var lazyLoadElement = $(target).find('.lazy-load');
            if (!lazyLoadElement.data('loaded')) {
                var youtubeUrl = lazyLoadElement.data('url');
                if (youtubeUrl) {
                    // Load the YouTube playlist iframe
                    lazyLoadElement.html('<iframe width="100%" height="315" src="https://www.youtube.com/embed/videoseries?list=' + youtubeUrl.split('list=')[1] + '" frameborder="0" allowfullscreen></iframe>');
                    lazyLoadElement.data('loaded', true);
                } else {
                    lazyLoadElement.html('<p>No YouTube playlist URL provided.</p>');
                }
            }
        }

        // Lazy load content for the third tab (Featured Image)
        if ($(target).is('#tab3')) {
            var lazyLoadElement = $(target).find('.lazy-load');
            if (!lazyLoadElement.data('loaded')) {
                var imageUrl = lazyLoadElement.data('url');
                if (imageUrl) {
                    lazyLoadElement.html('<img src="' + imageUrl + '" alt="Featured Image" style="max-width:100%; height:auto;"/>');
                    lazyLoadElement.data('loaded', true);
                } else {
                    lazyLoadElement.html('<p>No featured image available.</p>');
                }
            }
        }
    });

    // Lazy load footer when scrolled to the bottom
  

    // Initial setup: hide all tabs except the first one
    $('.tab').hide();
    $('#tab1').show(); // Show the first tab by default


    var footerLoaded = false;

    $(window).on('scroll', function() {
        if (!footerLoaded && ($(window).height() + $(window).scrollTop()) >= $(document).height() - 100) {
            footerLoaded = true; // Prevent multiple loads
            loadFooterContent();
        }
    });

    function loadFooterContent() {
        // Simulate an AJAX request to load footer content
        setTimeout(function() {
            var footerContent = '<p>This is the lazy-loaded footer content.</p>'; // Replace with actual content or AJAX call
            $('#lazy-footer .footer-content').html(footerContent);
        }, 500); // Simulate a delay for loading
    }
});