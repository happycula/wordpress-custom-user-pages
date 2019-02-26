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

/**
 * Is the given page one of our custom pages.
 *
 * @since  1.0.4
 * @param  int $page_id The page ID.
 * @return boolean
 */
function hcup_is_custom_page( $page_id ) {
    $pages = array(
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-login' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-after-login' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-after-logout' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-register' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-lostpassword' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-resetpassword' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-editprofile' ),
        get_option( HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME . '-pages-account' ),
    );

    if ( in_array( $page_id, $pages ) ) {
        return true;
    }

    return false;
}
