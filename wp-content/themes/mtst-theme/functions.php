<?php

function slug() { 
  global $post;
  return $post->post_name;
}

function themebs_enqueue_styles() {
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap-4.6.1-dist/css/bootstrap.min.css' );
  wp_enqueue_style( 'core', get_template_directory_uri() . '/assets/css/main.css' );
  if (slug()!='home' && get_post_type() == 'page') { // CSS complementar das páginas internas
    wp_enqueue_style( 'page', get_template_directory_uri() . '/assets/css/'. slug() .'.css' );
  }
  if (is_archive() || is_search()) { // CSS complementar para categorias e resulta da busca
    wp_enqueue_style( 'archive', get_template_directory_uri() . '/assets/css/archive.css' );
  }
  if (is_single()) { // CSS complementar para categorias e resulta da busca
    wp_enqueue_style( 'archive', get_template_directory_uri() . '/assets/css/single.css' );
  }
}
add_action( 'wp_enqueue_scripts', 'themebs_enqueue_styles');

function themebs_enqueue_scripts() {
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap-4.6.1-dist/js/bootstrap.bundle.min.js',
  array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'themebs_enqueue_scripts');
 

add_theme_support( 'title-tag' );

/*
 * Enable support for Post Thumbnails on posts and pages.
 */
add_theme_support( 'post-thumbnails' );

/*
 * Switch default core markup for search form, comment form, and comments
 * to output valid HTML5.
 */
add_theme_support( 'html5', array(
	'search-form',
	'comment-form',
	'comment-list',
	'gallery',
	'caption',
) );

/* Carrega os endpoints da API */

// require_once(get_template_directory() . "/api/api-spotfy.php");