<?php
/**
 * Scripts
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue admin scripts
 *
 * @since 1.0
 * @param string $hook Page hook
 * @return void
 */
function erm_load_admin_scripts( $hook ) {

    global $wp_version, $post, $pagenow, $typenow;

    $js_dir  = ERM_PLUGIN_URL . 'assets/js/';
    $css_dir = ERM_PLUGIN_URL . 'assets/css/';

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Only for Menu admin page
    if ( apply_filters( 'erm_load_admin_menu_page_scripts', erm_is_admin_menu_page_post_type(), $hook ) ) {

        // Fontawesome
        wp_enqueue_style( 'fontawesome', $css_dir.'font-awesome'.$suffix.'.css', array(), '4.3.0' );

        // Sweetalert
        wp_enqueue_style( 'sweetalert', $css_dir.'sweetalert.css', array(), '1.0.0' );

        // ERM style
        wp_enqueue_style( 'erm-admin-style', $css_dir.'erm-admin-style.css', array(), ERM_VERSION );

        // Magnific popup
        wp_enqueue_style( 'magnific-popup', $css_dir.'magnific-popup.css' );

        // Sweetalert
        wp_enqueue_script( 'sweetalert', $js_dir.'sweetalert.min.js', array(), '1.0.0', true );

        // Knockout
        wp_enqueue_script( 'knockout', $js_dir.'knockout.min.js', array(), '3.3.0', true );
        wp_enqueue_script( 'knockout-sortable', $js_dir.'knockout-sortable'.$suffix.'.js', array(), '0.11.0', true );

        // Media
        if( function_exists( 'wp_enqueue_media' ) && version_compare( $wp_version, '3.5', '>=' ) ) {
            wp_enqueue_media();
        }
        wp_enqueue_script( 'media-upload' );

        // Magnific-popup
        wp_enqueue_script( 'magnific-popup', $js_dir . 'jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );

        // ERM script
        wp_enqueue_script( 'erm-admin-scripts', $js_dir.'erm-admin-scripts.js', array('jquery','knockout', 'knockout-sortable'), ERM_VERSION, true );

        // Menu items
        global $post_id;
        $menu_items = erm_get_menu_items_data( $post->ID );
        wp_localize_script( 'erm-admin-scripts', 'erm_vars', array(
            'menu_id'       => $post_id,
            'menu_items'    => $menu_items,
            'editor_css'    => $css_dir.'erm-admin-tinymce.css',
            'use_new_media_35' => function_exists( 'wp_enqueue_media' ) ? 1 : 0,
            'notices' => array(
                'alert_delete' => __('Are you sure to delete?', 'erm'),
                'alert_confirm' => __('Yes, delete it!', 'erm')
            )
        ) );

    } else if ( $pagenow == 'edit.php' && ( $typenow == 'erm_menu_item' || $typenow == 'erm_menu' ) ) {
        wp_enqueue_style( 'erm-admin-cols-style', $css_dir.'erm-admin-cols-style.css', array(), ERM_VERSION );
    }

}
add_action( 'admin_enqueue_scripts', 'erm_load_admin_scripts', 100 );