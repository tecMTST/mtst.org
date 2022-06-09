<?php

/* Adiciona o Bootstrap */
function themebs_enqueue_styles() {
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap-4.6.1-dist/css/bootstrap.min.css' );
  wp_enqueue_style( 'normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
  wp_enqueue_style( 'core', get_template_directory_uri() . '/assets/css/main.css' );
}
add_action( 'wp_enqueue_scripts', 'themebs_enqueue_styles');
function themebs_enqueue_scripts() {
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap-4.6.1-dist/js/bootstrap.bundle.min.js',
  array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'themebs_enqueue_scripts');