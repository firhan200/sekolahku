<?php
function legal101_theme_support(){
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'legal101_theme_support');


function legal101_menus(){
    $location = array(
        'primary' => 'Top Menu',
        'primary_right' => 'Top Right Menu',
        'social_media_menu' => 'Social Media Menu',
        'company_menu' => 'Company Menu',
        'services_menu' => 'Services Menu'
    );

    register_nav_menus($location);
}

add_action('init', 'legal101_menus');

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
  if (in_array('current-menu-item', $classes) ){
    $classes[] = 'active ';
  }
  return $classes;
}


function legal101_register_styles(){
    wp_enqueue_style('legal101-style', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('legal101-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), '5.1.3', 'all');

    wp_enqueue_script('legal101-jquery', 'https://code.jquery.com/jquery-3.6.0.min.js');
}

add_action('wp_enqueue_scripts', 'legal101_register_styles');

add_theme_support('title-tag');

?>