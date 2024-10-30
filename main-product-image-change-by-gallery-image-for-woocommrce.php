<?php
/*
Plugin Name: Main Product Image Change By Gallery Image for WooCommrce
Plugin URI: https://github.com/pmbaldha/
Description: Change Product Main Image by product's gallery thumbnail image click in single product page.
Version: 0.1
Author: pmbaldha
Author URI: https://github.com/pmbaldha/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Main Product Image Change By Gallery Image for WooCommrce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
 
Main Product Image Change By Gallery Image for WooCommrce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Main Product Image Change By Gallery Image for WooCommrce. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function mpicbgifw_plugin_activate() {

    // Activation code here...
	update_option( 'woocommerce_enable_lightbox', '' );
}
register_activation_hook( __FILE__, 'mpicbgifw_plugin_activate' );

function mpicbgifw_enqueue_script() {
	if( is_singular( 'product' ) ) {
		wp_add_inline_script('woocommerce',  "( function( $ ) {
			$(document).on('click','.thumbnails .zoom', function(){
				$('.thumbnails .zoom').removeClass('highlight-thumbnail');
				$(this).addClass( 'highlight-thumbnail' )
				
				var photo_fullsize = $(this).attr('href');
				$('.woocommerce-main-image').attr('href',$(this).attr('href'));
				$('.woocommerce-main-image img').removeAttr('srcset')
				$('.woocommerce-main-image img').removeAttr('src').attr('src', photo_fullsize);
				return false;
			});
			} )( jQuery );"
		 );
		 
		 wp_add_inline_style('woocommerce-general' , '.highlight-thumbnail { border: 2px solid red;}');
		 
	}
	
}
add_action( 'wp_enqueue_scripts', 'mpicbgifw_enqueue_script' );

function mpicbgifw_add_featured_image_in_gallery( $gallery_images_arr, $product_obj) {
	$featured_image_arr = array( get_post_thumbnail_id( $product_obj->get_id() ) );
	$gallery_images_arr = array_merge($featured_image_arr , (array) $gallery_images_arr);
	return $gallery_images_arr;
}
add_filter( 'woocommerce_product_gallery_attachment_ids', 'mpicbgifw_add_featured_image_in_gallery', 10, 2 );
