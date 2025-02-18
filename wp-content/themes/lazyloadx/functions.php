<?php
function lazyloadx_theme_enqueue_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('jquery');
    wp_enqueue_script('lazy-load', get_template_directory_uri() . '/js/lazy-load.js', array('jquery'), null, true);
    wp_localize_script('lazy-load', 'frontend_ajax_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        )
    );
}
add_action('wp_enqueue_scripts', 'lazyloadx_theme_enqueue_scripts');
// Enable support for Post Thumbnails
add_theme_support('post-thumbnails');

// Add a meta box for YouTube Playlist URL
function add_youtube_playlist_meta_box() {
    add_meta_box(
        'youtube_playlist_meta_box', // ID
        'YouTube Playlist URL', // Title
        'render_youtube_playlist_meta_box', // Callback
        'post', // Post type
        'normal', // Context
        'high' // Priority
    );
}
add_action('add_meta_boxes', 'add_youtube_playlist_meta_box');


function render_youtube_playlist_meta_box($post) {

    wp_nonce_field('youtube_playlist_nonce_action', 'youtube_playlist_nonce');


    $youtube_playlist = get_post_meta($post->ID, 'youtube_playlist', true);


    echo '<label for="youtube_playlist">Enter YouTube Playlist URL:</label>';
    echo '<input type="text" id="youtube_playlist" name="youtube_playlist" value="' . esc_attr($youtube_playlist) . '" style="width:100%;"/>';
}

function save_youtube_playlist_meta_box($post_id) {
    if (!isset($_POST['youtube_playlist_nonce']) || !wp_verify_nonce($_POST['youtube_playlist_nonce'], 'youtube_playlist_nonce_action')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['youtube_playlist'])) {
        update_post_meta($post_id, 'youtube_playlist', sanitize_text_field($_POST['youtube_playlist']));
    }
}
add_action('save_post', 'save_youtube_playlist_meta_box');



add_action('wp_ajax_load_youtube_playlist', 'load_youtube_playlist');
add_action('wp_ajax_nopriv_load_youtube_playlist', 'load_youtube_playlist');

function load_youtube_playlist() {
    $post_id = intval($_POST['post_id']);
    $youtube_playlist = get_post_meta($post_id, 'youtube_playlist', true);
    
    if ($youtube_playlist) {
        // Extract the playlist ID from the URL
        parse_str(parse_url($youtube_playlist, PHP_URL_QUERY), $query);
        $playlist_id = isset($query['list']) ? $query['list'] : '';

        if ($playlist_id) {
            echo '<iframe width="100%" height="815" src="https://www.youtube.com/embed/videoseries?list=' . esc_attr($playlist_id) . '" frameborder="0" allowfullscreen></iframe>';
        } else {
            echo '<p>Invalid YouTube playlist URL.</p>';
        }
    } else {
        echo '<p>No YouTube playlist URL provided.</p>';
    }
    
    wp_die();
}

add_action('wp_ajax_load_featured_image', 'load_featured_image');
add_action('wp_ajax_nopriv_load_featured_image', 'load_featured_image');

function load_featured_image() {
    $post_id = intval($_POST['post_id']);
    
    if (has_post_thumbnail($post_id)) {
        $image_url = get_the_post_thumbnail_url($post_id);
        echo '<img src="' . esc_url($image_url) . '" alt="Featured Image" style="max-width:100%; height:auto;"/>';
    } else { 
        echo '<p>No featured image available.</p>';
    }
    
    wp_die(); 
}

function load_lazy_footer() {
    ob_start();
    get_template_part('lazy-footer'); 
    $content = ob_get_clean();
    wp_send_json_success($content);
}
add_action('wp_ajax_load_lazy_footer', 'load_lazy_footer');
add_action('wp_ajax_nopriv_load_lazy_footer', 'load_lazy_footer');