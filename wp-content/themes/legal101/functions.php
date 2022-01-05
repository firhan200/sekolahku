<?php
function legal101_register_styles(){
    wp_enqueue_style('legal101-style', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('legal101-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), '5.1.3', 'all');
}

add_action('wp_enqueue_scripts', 'legal101_register_styles');

add_theme_support('title-tag');

?>