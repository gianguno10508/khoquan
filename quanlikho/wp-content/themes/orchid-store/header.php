<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Orchid_Store
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<?php wp_head(); ?>
	<?php echo do_shortcode('[gtranslate]'); ?>
</head>

<body <?php body_class(); ?>>
	<?php 
	if( function_exists( 'wp_body_open' ) ) { 
		wp_body_open(); 
	} 
	?>
	<div id="page" class="site __os-page-wrap__">

		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'orchid-store' ); ?></a>

		<?php
		/**
        * Hook - orchid_store_header.
        *
        * @hooked orchid_store_header_action - 10
        */
        do_action( 'orchid_store_header' );
        ?>
        
        <div id="content" class="site-content">