<?php
function lazyloadx_theme_enqueue_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('jquery');
    wp_enqueue_script('lazy-load', get_template_directory_uri() . '/js/lazy-load.js', array('jquery'), null, true);
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

// Render the meta box
function render_youtube_playlist_meta_box($post) {
    // Use nonce for verification
    wp_nonce_field('youtube_playlist_nonce_action', 'youtube_playlist_nonce');

    // Retrieve existing value from the database
    $youtube_playlist = get_post_meta($post->ID, 'youtube_playlist', true);

    // Display the form field
    echo '<label for="youtube_playlist">Enter YouTube Playlist URL:</label>';
    echo '<input type="text" id="youtube_playlist" name="youtube_playlist" value="' . esc_attr($youtube_playlist) . '" style="width:100%;"/>';
}

// Save the meta box data
function save_youtube_playlist_meta_box($post_id) {
    // Check nonce for security
    if (!isset($_POST['youtube_playlist_nonce']) || !wp_verify_nonce($_POST['youtube_playlist_nonce'], 'youtube_playlist_nonce_action')) {
        return;
    }

    // Check if the user has permission to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the YouTube playlist URL
    if (isset($_POST['youtube_playlist'])) {
        update_post_meta($post_id, 'youtube_playlist', sanitize_text_field($_POST['youtube_playlist']));
    }
}
add_action('save_post', 'save_youtube_playlist_meta_box');