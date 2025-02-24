<?php

function load_tab_content() {
    check_ajax_referer('my_ajax_nonce', 'security');

    $tab_action = isset($_GET['tab_action']) ? sanitize_text_field($_GET['tab_action']) : '';
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $response = '';

    switch ($tab_action) {
        case 'load_youtube_playlist':
            $youtube_playlist = get_post_meta($post_id, 'youtube_playlist', true);
            parse_str(parse_url($youtube_playlist, PHP_URL_QUERY), $query);
            $playlist_id = isset($query['list']) ? $query['list'] : '';
    
            if ($playlist_id) {
                echo '<iframe width="100%" height="815" src="https://www.youtube.com/embed/videoseries?list=' . esc_attr($playlist_id) . '" frameborder="0" allowfullscreen></iframe>';
            } else {
                echo '<p>Invalid YouTube playlist URL.</p>';
            }
            break;
        case 'load_featured_image':
            if (has_post_thumbnail($post_id)) {
                $image_url = get_the_post_thumbnail_url($post_id);
                echo '<img src="' . esc_url($image_url) . '" alt="Featured Image" style="max-width:100%; height:auto;"/>';
            } else { 
                echo '<p>No featured image available.</p>';
            }
            break;
        default:
            $response = "<p>Invalid request.</p>";
            break;
    }

    wp_die();
}
add_action('wp_ajax_load_tab_content', 'load_tab_content');
add_action('wp_ajax_nopriv_load_tab_content', 'load_tab_content');


function load_lazy_footer() {
    check_ajax_referer('my_ajax_nonce', 'security');

    ob_start();
    ?>
    <footer class="site-footer">
        <p>&copy; <?php echo date('Y'); ?> My Website. All rights reserved.</p>
    </footer>
    <?php
    $footer_content = ob_get_clean();
    
    wp_send_json_success($footer_content);
}
add_action('wp_ajax_load_lazy_footer', 'load_lazy_footer');
add_action('wp_ajax_nopriv_load_lazy_footer', 'load_lazy_footer');