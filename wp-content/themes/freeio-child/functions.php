<?php

function freeio_child_enqueue_styles() {
	wp_enqueue_style( 'freeio-child-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'freeio_child_enqueue_styles', 200 );