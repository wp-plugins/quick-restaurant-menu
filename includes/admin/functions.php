<?php
/**
 * Admin functions
 *
 * @package     ERM
 * @subpackage  Admin
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Determines if is ERM admin Menu page
 *
 * @since 1.0
 * @return bool True is is admin Menu post type page
 */
function erm_is_admin_menu_page_post_type() {
    global $pagenow, $typenow;

    $ret = false;

    if ( 'erm_menu' == $typenow && ( 'post.php' ==  $pagenow || 'post-new.php' == $pagenow ) ) {
        $ret = true;
    }

    return (bool) apply_filters( 'erm_is_admin_menu_page', $ret );
}



/**
 * Get menu items array data for post type erm_menu
 *
 * @since 1.0
 * @param $post_id
 * @return array
 */
function erm_get_menu_items_data( $post_id ) {

    // Get meta
    $menu_items = get_post_meta( $post_id, '_erm_menu_items', true );
    if ( empty($menu_items) ) return array();

    // Split to get ids
    $menu_items = preg_split('/,/', $menu_items);

    // Array to return
    $result = array();

    // Get data foreach menu item id
    foreach( $menu_items as $id ) {

        $data = erm_get_menu_item_data( $id );
        if ( ! empty($data) )
            $result[] = $data;
    }
    return $result;
}

/**
 * Get post type erm_menu_item data
 *
 * @since 1.0
 * @param $id
 * @return array
 */
function erm_get_menu_item_data( $id ) {

    $post = get_post( $id );

    if ( $post && get_post_type($id) == 'erm_menu_item' ) {

        $visible = get_post_meta( $id, '_erm_visible', true );

        if ( has_post_thumbnail( $id ) ) {
            $thumbnail_id = get_post_thumbnail_id($id);
            $image_src_thumb = wp_get_attachment_image_src( $thumbnail_id );
            $image_src_thumb = isset($image_src_thumb[0]) ? $image_src_thumb[0] : '';
            $image_src_big = wp_get_attachment_image_src( $thumbnail_id, 'full');
            $image_src_big = isset($image_src_big[0]) ? $image_src_big[0] : '';
            $post_image = get_post($thumbnail_id);
            $image_title = $post_image->post_excerpt;
            $image_desc = $post_image->post_content;
        } else {
            $thumbnail_id = 0;
            $image_src_thumb = '';
            $image_src_big = '';
            $image_title = '';
            $image_desc = '';
        }

        $prices = get_post_meta($id, '_erm_prices', true);

        return array(
            'id'            => intval($id),
            'type'          => get_post_meta( $id, '_erm_type', true ),
            'visible'       => $visible ? 1 : 0,
            'title'         => $post->post_title,
            'content'       => $post->post_content,
            'image_id'      => intval($thumbnail_id),
            'src_thumb'     => $image_src_thumb,
            'src_big'       => $image_src_big,
            'image_title'   => $image_title,
            'image_desc'    => $image_desc,
            'prices'        => $prices,
            'link'          => get_edit_post_link( $id )
        );
    }

    return array();
}


