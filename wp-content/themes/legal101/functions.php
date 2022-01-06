<?php

function legal101_theme_support(){
    add_theme_support( 'custom-logo' );
    add_theme_support('title-tag');
    add_theme_support('widgets');
    add_theme_support('widgets-block-editor');
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


/* Widgets */

add_action('widgets_init', 'legal101_register_sidebars');
     
function legal101_register_sidebars() { 
    register_sidebar(array(
      'name'=> __( 'Company Information Footer', 'textdomain' ),  
      'id'=> 'company-information-footer',  
      'description'=> 'Change Company Information that display on footer.',  
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="">',
      'after_title' => '</h2>',        
    ));

    register_sidebar(array(
      'name'=> __( 'Login Image Icon', 'textdomain' ),  
      'id'=> 'legal101-login-icon',  
      'description'=> 'Change Login Image Icon.',  
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="">',
      'after_title' => '</h2>',        
    ));

    register_sidebar(array(
      'name'=> __( 'Social Image Icon', 'textdomain' ),  
      'id'=> 'legal101-social-icon',  
      'description'=> 'Change Social Image Icon.',  
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="">',
      'after_title' => '</h2>',        
    ));

    register_sidebar(array(
      'name'=> __( 'Login - Left Content', 'textdomain' ),  
      'id'=> 'legal101-login-left-content',  
      'description'=> 'Login - Left Content',  
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="">',
      'after_title' => '</h2>',        
    ));

    register_sidebar(array(
      'name'=> __( 'Login - Contact Us', 'textdomain' ),  
      'id'=> 'legal101-contact-us',  
      'description'=> 'Login - Contact Us',  
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="">',
      'after_title' => '</h2>',        
    ));
}

?>