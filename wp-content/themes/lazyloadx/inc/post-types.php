<?php
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
