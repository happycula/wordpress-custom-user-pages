<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/includes
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Happycula Custom User Pages Activator class.
 */
class Happycula_Custom_User_Pages_Activator {

    /**
     * Activate the plugin.
     *
     * Creates all pages required by the plugin.
     *
     * @since 1.0.0
     */
    public static function activate() {

        $pages = array(
            __( 'account', 'happycula-custom-user-pages' ) => array(
                'title'   => __( 'My account', 'happycula-custom-user-pages' ),
                'content' => '[hcup_account]',
            ),
            __( 'sign-in', 'happycula-custom-user-pages' ) => array(
                'title'   => __( 'Sign In', 'happycula-custom-user-pages' ),
                'content' => '[hcup_login_form]',
            ),
            __( 'register', 'happycula-custom-user-pages' ) => array(
                'title'   => __( 'Register', 'happycula-custom-user-pages' ),
                'content' => '[hcup_register_form]',
            ),
            __( 'lost-password', 'happycula-custom-user-pages' ) => array(
                'title'   => __( 'Forgot your password?', 'happycula-custom-user-pages' ),
                'content' => '[hcup_lostpassword_form]',
            ),
            __( 'reset-password', 'happycula-custom-user-pages' ) => array(
                'title'   => __( 'Reset your password', 'happycula-custom-user-pages' ),
                'content' => '[hcup_resetpassword_form]',
            ),
            __( 'edit-profile', 'happycula-custom-user-pages' ) => array(
                'title'   => __( 'Edit my profile', 'happycula-custom-user-pages' ),
                'content' => '[hcup_editprofile_form]',
            ),
        );

        foreach ( $pages as $slug => $page ) {
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_title'     => $page['title'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'comment_status' => 'closed',
                        'ping_status'    => 'closed',
                        'post_name'      => $slug,
                    )
                );
            }
        }

    }

}
