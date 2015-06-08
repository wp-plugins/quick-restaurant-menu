<?php
/**
 * Shortcodes
 *
 * @package     ERM
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode Menu one column
 *
 * @since 1.0
 */
function erm_shortcode_menu( $atts, $content = null ) {

    global $post;
    $post_id = is_object( $post ) ? $post->ID : 0;

    $atts = shortcode_atts( array(
        'id' 	        => $post_id
    ), $atts, 'erm_menu' );

    $post_id = $atts['id'];

    if ( get_post_type( $post_id ) != 'erm_menu' ) { return; }

    // Title & description Menu
    $menu_post = get_post( $post_id );
    $html  = '<div class="erm_menu">';
    $html .= '<h1 class="erm_title">'.$menu_post->post_title.'</h1>';
    $html .= '<div class="erm_desc">'.apply_filters('the_content', $menu_post->post_content).'</div>';

    // Menu items
    $menu_items = get_post_meta( $post_id, '_erm_menu_items', true );
    if ( empty($menu_items) ) return;

    $menu_items = preg_split('/,/', $menu_items);

    $html .= '<ul class="erm_menu_content">';

    foreach( $menu_items as $item_id ) {

        $visible = get_post_meta( $item_id, '_erm_visible', true );
        if ( $visible != 1 ) continue;

        $post = get_post( $item_id );
        $type = get_post_meta( $item_id, '_erm_type', true );

        if ( $type == 'section' ) {
            $html .= '<li class="erm_section">';
            $html .= '<h2 class="erm_section_title">'.$post->post_title.'</h2>';
            $html .= '<div class="erm_section_desc">'.apply_filters( 'the_content', $post->post_content ).'</div>';
            $html .= '</li>';
        }
        else if ( $type == 'product' ) {

            $has_thumbnail = has_post_thumbnail( $item_id );
            $html .= '<li class="erm_product '.($has_thumbnail ? 'with_image' : 'no_image').'">';
            if ( $has_thumbnail ) {
                $image_id = get_post_thumbnail_id( $item_id );
                $src_thumb = erm_get_image_src( $image_id, 'medium' );
                $src_full = erm_get_image_src( $image_id, 'full' );
                $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                $post_image = get_post( $image_id );
                $caption = $post_image->post_excerpt;
                $desc = $post_image->post_content;
                $html .= '<a class="image-popup" target="_blank" href="'.$src_full.'" data-caption="'.esc_attr($caption).'" data-desc="'.esc_attr($desc).'"><img class="erm_product_image" alt="'.esc_attr($alt).'" src="'.$src_thumb.'"></a>';
            }
            $html .= '<h3 class="erm_product_title">'.$post->post_title.'</h3>';

            $html .= '<div class="erm_product_price">';
            $prices = get_post_meta( $item_id, '_erm_prices', true );
            if ( !empty($prices) ) {
                $html .= '<ul>';
                foreach( $prices as $price ) {
                    $html .= '<li><span class="name">'.$price['name'].'</span><span class="price">'.apply_filters('erm_filter_price', $price['value']).'</span></li>';
                }
                $html .= '</ul>';
            }
            $html .= '</div>';

            $html .= '<div class="erm_product_desc">'.apply_filters('the_content', $post->post_content).'</div>';

            $html .= '<div class="clear"></div>';
            $html .= '</li>';
        }
    }

    $html .= '</ul>';

    $html .= '<div class="erm_footer_desc">'.apply_filters('the_content', get_post_meta( $menu_post->ID, '_erm_footer_menu', true )).'</div>';

    $html .= '</div>';

    return $html;
}
add_shortcode( 'erm_menu', 'erm_shortcode_menu' );


/**
 * Shortcode Menu Week
 *
 * @since 1.1
 */
function erm_shortcode_menu_week( $atts, $content = null ){

    global $post;
    $post_id = is_object( $post ) ? $post->ID : 0;

    $atts = shortcode_atts( array(
        'id' 	        => $post_id
    ), $atts, 'erm_menu' );

    $post_id = $atts['id'];

    if ( get_post_type( $post_id ) != 'erm_menu_week' ) { return; }

    $rules = get_post_meta( $post_id, 'erm_week_rules', true );
    if (empty($rules)) { return; }

    // Get today
    $now = current_time('timestamp',0);
    $now_day = current_time('w',0); // 0-6 0=sunday
    //echo '<pre>NOW: '; print_r( $now ); echo '</pre>';
    //echo '<pre>NOW DAY: '; print_r( $now_day ); echo '</pre>';
    //echo '<pre>'; print_r( current_time('mysql',0) ); echo '</pre>';

    //$blogtime = current_time( 'mysql',0);
    //list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '/([^0-9])/', $blogtime );
    $hour = current_time('H',0);
    $minute = current_time('i',0);
    $day_minutes = 60*intval($hour)+intval($minute);
    //echo '<pre>HOUR: '; print_r( $hour ); echo '</pre>';
    //echo '<pre>MINUTES: '; print_r( $minute ); echo '</pre>';
    //echo '<pre>DAY MINUTES: '; print_r( $day_minutes ); echo '</pre>';

    // Check each rule
    foreach( $rules as $rule ) {

        //echo '<pre>RULE: '; print_r( $rule ); echo '</pre>';

        // Check if day is included
        $days_rule = array();
        $index = 0;
        foreach($rule['week'] as $day) {
            if ($day === 'true' ) {
                $days_rule[] = $index;
            }
            $index++;
        }
        if ( !in_array($now_day,$days_rule) ) {
            continue;
        }

        // Check hours
        list( $hour1, $min1 ) = preg_split('/:/', $rule['begin']);
        list( $hour2, $min2 ) = preg_split('/:/', $rule['end']);
        $minutes_begin = 60*intval($hour1)+intval($min1);
        $minutes_end = 60*intval($hour2)+intval($min2);
        //echo '<pre>Begin: '; print_r( $minutes_begin ); echo '</pre>';
        //echo '<pre>End: '; print_r( $minutes_end ); echo '</pre>';

        if ( $minutes_begin<=$day_minutes && $minutes_end>=$day_minutes ) {
            //echo '<h1>ESTE COINCIDE</h1>';
            echo do_shortcode('[erm_menu id='.$rule['menu_id'].']');
            break;
        }
    }

}
add_shortcode( 'erm_menu_week', 'erm_shortcode_menu_week' );