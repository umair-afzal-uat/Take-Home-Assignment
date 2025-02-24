<?php
function lazyloadx_theme_enqueue_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('jquery');
    wp_enqueue_script('lazy-load', get_template_directory_uri() . '/js/lazy-load.js', array('jquery'), null, true);
    wp_localize_script('lazy-load', 'frontend_ajax_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'security' => wp_create_nonce('my_ajax_nonce')
        )
    );
}
add_action('wp_enqueue_scripts', 'lazyloadx_theme_enqueue_scripts');

require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/ajax-handler.php';
