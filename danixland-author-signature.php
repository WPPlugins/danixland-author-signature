<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* 
Plugin Name:    danixland author signature
Description:    Enqueue a signature on your posts and pages.
Plugin URI:     http://danixland.net/?p=3694
Version:        1.1
Author:         Danilo 'danix' Macr&igrave;
Author URI:     http://danixland.net
License:        GPL2
License URI:    https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dnxasi

*/

/**
 * Add plugin i18n domain: dnxasi
 * @since 0.3
 */
load_plugin_textdomain('dnxasi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

/**
 * Let's load our helper file ( utility functions )
 * @since 1.1
 */
require( dirname( __FILE__ ) . '/inc/dnxasi_helper.php' );

if ( is_admin()) {
    require_once( dirname(__FILE__) . '/inc/dnxasi-settings.php' );
}

// enqueue our js for the modal uploader
function dnxasi_enqueue_scripts($hook) {
    wp_register_script( 'dnxasi_uploader_modal', plugins_url('/js/dnxasi_uploader_modal.js', __FILE__) );
    $translation = array(
        'frameTitle'    => __('Select your signature', 'dnxasi'),
        'buttonText'    => __('Use Image', 'dnxasi'),
        'placeholder'   => plugins_url('/img/placeholder.png', __FILE__)
    );
    if ( current_user_can( 'publish_posts' ) ) {
        if ( 'profile.php' != $hook ) {
            return;
        }
        wp_enqueue_media();
        wp_localize_script( 'dnxasi_uploader_modal', 'data', $translation );
        wp_enqueue_script( 'dnxasi_uploader_modal', false , array('jquery'), '0.1' );
    }
}
add_action('admin_enqueue_scripts', 'dnxasi_enqueue_scripts');

/**
 * Adds additional user fields
 * more info: http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
 */
function dnxasi_add_signature( $user ) { 
    if ( ! current_user_can('publish_posts') )
        return false;

    $imagesrc = get_the_author_meta( 'dnxasi_meta_signature', $user->ID );
    $imagesrc = ( ! empty($imagesrc) ) ? $imagesrc : plugins_url('/img/placeholder.png', __FILE__);
?>
 
    <h2><?php _e( 'Profile signature', 'dnxasi' ); ?></h2>

    <p><?php _e('if you don\'t select a signature image nothing will be displayed in the content of your posts or pages.', 'dnxasi'); ?></p>

    <table class="form-table">

        <tr>
            <th><label for="dnxasi_meta_signature"><?php _e( 'add your signature image', 'dnxasi' ); ?></label></th>
            <td>
                <img id="dnxasi_signature_preview" src="<?php echo esc_url( $imagesrc ); ?>" style="width:450px"><br />
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="dnxasi_meta_signature" id="dnxasi_meta_signature" value="<?php echo esc_url_raw( get_the_author_meta( 'dnxasi_meta_signature', $user->ID ) ); ?>" class="regular-text" readonly="readonly">
                <!-- Outputs the save button -->
                <input type='button' class="button-secondary" value="<?php _e( 'Select Signature', 'dnxasi' ); ?>" id="dnxasi_uploadimage">
                <!-- Outputs the reset button -->
                <input type='button' class="button-secondary" value="<?php _e( 'Remove Signature', 'dnxasi' ); ?>" id="dnxasi_deleteimage"><br>
                <span class="description"><?php _e( 'Select and preview your signature image.', 'dnxasi' ); ?></span>
            </td>
        </tr>
 
    </table><!-- end form-table -->
<?php } // dnxasi_add_signature
add_action( 'show_user_profile', 'dnxasi_add_signature' );
add_action( 'edit_user_profile', 'dnxasi_add_signature' );

/**
* Saves additional user fields to the database
*/
function dnxasi_save_signature( $user_id ) {
 
    // only saves if the current user can edit user profiles
    if ( ! current_user_can( 'edit_user', $user_id ) )
        return false;
 
    update_usermeta( $user_id, 'dnxasi_meta_signature', $_POST['dnxasi_meta_signature'] );
}
 
add_action( 'personal_options_update', 'dnxasi_save_signature' );
add_action( 'edit_user_profile_update', 'dnxasi_save_signature' );

/**
* Display the signature image only for authors who set it in the admin panel
* and only in single view.
*/
function dnxasi_display_content($content) {
    global $page;
    $options = get_option('dnxasi_settings');
    $signature = get_the_author_meta('dnxasi_meta_signature');
    $alignment = ( ! empty($options['dnxasi_signature_position']) ) ? $options['dnxasi_signature_position'] : 'right';
    $signature_size = ( ! empty($options['dnxasi_signature_size']) ) ? $options['dnxasi_signature_size'] : '300';
    if ( is_single() && is_main_query() && ( '' != $signature ) ) {
        if ( ! dnxasi_is_post_paginated() || ( $page === dnxasi_post_last_page() ) ) {
            $new_content = '<figure class="dnxasi_signature"><img class="dnxasi_signature_image align' . $alignment . '" src="' . $signature . '" width="' . $signature_size . '" alt="' . get_the_author_meta('display_name') . '"></figure>';
            $content .= $new_content;
        }
    }
    return $content;
}
add_filter( 'the_content', 'dnxasi_display_content' );
