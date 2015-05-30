<?php
/**
 * Misc Functions
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Get src for image id
 *
 * @since 1.0
 *
 * @param $image_id
 * @param string $size
 * @return bool
 */
function erm_get_image_src( $image_id, $size = 'thumbnail' ) {

    if ( $size == 'full' || $size == 'large' || $size == 'medium' || $size == 'thumbnail' ) {
        $image_data = wp_get_attachment_image_src( (int)$image_id, $size );
        return $image_data[0];
    }
    return false;
}

/**
 * Test if content has shortcode
 * WP 3.5 does not have this function
 *
 * @since 1.0
 */
if ( !function_exists('has_shortcode') ) {
    function has_shortcode( $content, $tag ) {
        if ( false === strpos( $content, '[' ) ) {
            return false;
        }

        if ( shortcode_exists( $tag ) ) {
            preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
            if ( empty( $matches ) )
                return false;

            foreach ( $matches as $shortcode ) {
                if ( $tag === $shortcode[2] ) {
                    return true;
                } elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
                    return true;
                }
            }
        }
        return false;
    }
    function shortcode_exists( $tag ) {
        global $shortcode_tags;
        return array_key_exists( $tag, $shortcode_tags );
    }
}

/**
 * Filter price to add currency
 *
 * @since 1.0
 * @param $price
 * @return string
 */
function erm_filter_price( $price ) {
    $currency = ERM()->settings->get('erm_currency');

    if ( empty($currency) ) return $price;

    $position = ERM()->settings->get('erm_currency_position');
    if ( $position == 'before' ) {
        return $currency.$price;
    } else {
        return $price.$currency;
    }
}
add_filter( 'erm_filter_price', 'erm_filter_price' );
