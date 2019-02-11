<?php
/**
 * Utility functions for users to use in their themes.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/includes
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Return the url of a custom page.
 *
 * @since  1.0.0
 * @param  string  $page     Name of the page.
 * @param  boolean $relative Should the returned url be relative. Default: false.
 * @return string|false
 */
function hcup_page_url( $page, $relative = false ) {

    $url = get_permalink( get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-' . $page, 0 ) );
    if ( $relative ) {
        $url = str_replace( home_url(), '', $url );
    }

    return $url;
}
