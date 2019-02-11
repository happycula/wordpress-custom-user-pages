<?php
/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/includes
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Happycula Custom User Pages Uninstaller class.
 */
class Happycula_Custom_User_Pages_Uninstaller {

    /**
     * Uninstall the plugin.
     *
     * Removes all options and pages created by the plugin.
     *
     * @since 1.0.0
     */
    public static function uninstall() {

        delete_option( 'happycula-custom-user-pages-pages-login' );
        delete_option( 'happycula-custom-user-pages-pages-after-login' );
        delete_option( 'happycula-custom-user-pages-pages-after-logout' );
        delete_option( 'happycula-custom-user-pages-pages-register' );
        delete_option( 'happycula-custom-user-pages-pages-lostpassword' );
        delete_option( 'happycula-custom-user-pages-pages-resetpassword' );
        delete_option( 'happycula-custom-user-pages-pages-editprofile' );
        delete_option( 'happycula-custom-user-pages-pages-account' );
        delete_option( 'happycula-custom-user-pages-recaptcha-site-key' );
        delete_option( 'happycula-custom-user-pages-recaptcha-secret-key' );

        // If we created pages on activation, remove them.
        $pages = array(
            __( 'account', 'happycula-custom-user-pages' ),
            __( 'sign-in', 'happycula-custom-user-pages' ),
            __( 'register', 'happycula-custom-user-pages' ),
            __( 'lost-password', 'happycula-custom-user-pages' ),
            __( 'reset-password', 'happycula-custom-user-pages' ),
            __( 'edit-profile', 'happycula-custom-user-pages' ),
        );

        foreach ( $pages as $slug ) {
            $query = new WP_Query( 'pagename=' . $slug );
            if ( $query->have_posts() ) {
                wp_delete_post( $query->posts[0]->ID, true );
            }
        }

    }

}
