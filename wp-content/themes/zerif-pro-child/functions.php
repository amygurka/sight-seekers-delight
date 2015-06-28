<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
    // wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-style')  );
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	wp_enqueue_style( 'zerif_font', 'http://fonts.googleapis.com/css?family=Lato:300,400,700,400italic|Montserrat:700|Homemade+Apple');
	wp_enqueue_style( 'zerif_font_all', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600italic,600,700,700italic,800,800italic');
	wp_enqueue_style( 'zerif_bootstrap_style', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'zerif_font-awesome_style', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), 'v1');
	wp_enqueue_style( 'zerif_style', get_stylesheet_uri(), array('zerif_font-awesome_style','zerif_bootstrap_style'),'v1' );
	if ( wp_is_mobile() ){
		wp_enqueue_style( 'zerif_style_mobile', get_template_directory_uri() . '/css/style-mobile.css', array('zerif_font-awesome_style','zerif_bootstrap_style', 'zerif_style'),'v1' );
	}
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-style')  );
}