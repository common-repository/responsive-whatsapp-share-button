<?php
/*
Plugin Name:  Responsive Share Button
Plugin URI:   https://www.freewebmentor.com
Description:  This plugin will add a WhatsApp share button into your every posts when visitor view your site mobile device.
Version:      1.1
Author: Prem Tiwari
Author URI: https://www.freewebmentor.com
License: GPL v3
*/

//--------- include scripts & style file---------------- //
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function rsb_whatsapp_style() {
	wp_enqueue_style( 'rsb-styles', plugins_url( 'style.css', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'rsb_whatsapp_style' );

#create table when activate the plugins
function rsb_SocialSharePlugin() {
	echo '<h1>FM Social Share Settings</h1>';
	echo '<form method="post">
	<table class="form-table">
	<tbody>
	<th scope="row">
		<label for="cj_email">Above Content </label>
	</th>
	<td>
		<select name="beforepost">
			<option value="1">Yes</option>
			<option value="0">No</option>
		</select>	
	</td>
	</tr>
	<tr>
	<th scope="row">
		<label for="cj_frequency">Below Content</label>
	</th>
	<td>
		<select name="afterpost">
			<option value="1">Yes</option>
			<option value="0">No</option>
		</select>
	</td>
	</tr>
	</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="publish" id="publish" class="button button-primary" value="Save Changes">
	</p>
	</form>';

	if ( isset( $_REQUEST['publish'] ) ) {
		$beforepost = filter_var( $_POST['beforepost'], FILTER_SANITIZE_NUMBER_INT );
		$afterpost  = filter_var( $_POST['afterpost'], FILTER_SANITIZE_NUMBER_INT );

		if ( get_option( 'Fmbefore_blogpost' ) && get_option( 'Fmafter_blogpost' ) ) {
			//add the option table
			add_option( 'Fmbefor_blogpost', $beforepost, '', 'yes' );
			add_option( 'Fmafter_blogpost', $afterpost, '', 'yes' );
		} else {
			//update the option table
			update_option( 'Fmbefor_blogpost', $beforepost, '', 'yes' );
			update_option( 'Fmafter_blogpost', $afterpost, '', 'yes' );
		}
		echo '<div class="updated notice notice-success is-dismissible below-h2" id="message"><p>Settings updated. </p></div>';
	}
}

#add in the admin menu bar
function rsb_AddAmin_menu_rsb_SocialSharePlugin() {
	add_options_page( 'Fm WhatsApp Share Button', 'WhatsApp Share Button', 'manage_options', 'fm-whatsApp-share', rsb_SocialSharePlugin );
}

add_action( 'admin_menu', 'rsb_AddAmin_menu_rsb_SocialSharePlugin' );

/**
 * Set the contents fro post
 */
if ( get_option( 'Fmbefor_blogpost' ) == 1 ) {
	add_filter( 'the_content', 'rsb_before_blogpost' );
}
if ( get_option( 'Fmafter_blogpost' ) == 1 ) {
	add_filter( 'the_content', 'rsb_after_blogpost' );
}

#Add custom text before every post
function rsb_before_blogpost( $content ) {
	if ( is_single() ) {
		$beforeContents = '<a href="whatsapp://send?text=' . get_the_title() . ' - ' . get_permalink() . '" class="wabtn">Share this on WhatsApp</a>';

		$content = $beforeContents . $content;
	}

	return $content;
}

#Add custom text after every post
function rsb_after_blogpost( $content ) {
	if ( is_single() ) {
		$content .= '<a href="whatsapp://send?text=' . get_the_title() . ' - ' . get_permalink() . '" class="wabtn">Share this on WhatsApp</a>';
	}

	return $content;
}
