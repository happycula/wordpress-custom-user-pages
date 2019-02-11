<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/admin
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Happycula Custom User Pages Admin class.
 */
class Happycula_Custom_User_Pages_Admin {

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Redirects user if needed.
     *
     * Redirects the user to the custom edit profile page instead of wp-admin/profile.php.
     * Redirects the user to the custon account page if it tries to access admin.
     *
     * @since 1.0.0
     */
    public function maybe_redirect() {

        if ( ! current_user_can( 'edit_posts' ) ) {
            $page = 'account';
            if ( isset( $_SERVER['REQUEST_URI'] ) ) {
                $request_uri = wp_unslash( $_SERVER['REQUEST_URI'] );
                if ( strpos( $request_uri, 'wp-admin/profile.php' ) ) {
                    $page = 'editprofile';
                }
            }

            wp_safe_redirect( get_permalink( get_option( $this->plugin_name . '-pages-' . $page, 0 ) ) );
            exit;
        }

    }

    /**
     * Add link to settings page.
     *
     * @since 1.0.0
     * @param array $links Array of links.
     */
    public function add_action_links( $links ) {

        $settings_url = admin_url( 'options-general.php?page=' . $this->plugin_name );
        $my_links     = array( '<a href="' . $settings_url . '">' . __( 'Settings', 'happycula-custom-user-pages' ) . '</a>' );

        return array_merge( $links, $my_links );
    }

    /**
     * Add custom options page to menu.
     *
     * @since 1.0.0
     */
    public function add_custom_options_page() {

        add_options_page(
            __( 'Happycula Custom User Pages Settings', 'happycula-custom-user-pages' ),
            __( 'Custom User Pages', 'happycula-custom-user-pages' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_settings_page' )
        );
    }

    /**
     * Display settings page.
     *
     * @since 1.0.0
     */
    public function display_settings_page() {

        if ( current_user_can( 'manage_options' ) ) {
            include dirname( __FILE__ ) . '/partials/happycula-custom-user-pages-admin-settings.php';
        } else {
            _esc_html_e( 'You don\'t have access to this page.' );
        }

    }

    /**
     * Register options for the plugin.
     *
     * @since 1.0.0
     */
    public function register_settings() {

        add_settings_section(
            $this->plugin_name . '-pages',
            __( 'Pages', 'happycula-custom-user-pages' ),
            array( $this, 'settings_section_pages' ),
            $this->plugin_name
        );
        add_settings_field(
            $this->plugin_name . '-pages-login',
            __( 'Sign in page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_login' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-login' );
        add_settings_field(
            $this->plugin_name . '-pages-after-login',
            __( 'After sign in, redirect to page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_after_login' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-after-login' );
        add_settings_field(
            $this->plugin_name . '-pages-after-logout',
            __( 'After sign out, redirect to page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_after_logout' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-after-logout' );
        add_settings_field(
            $this->plugin_name . '-pages-register',
            __( 'Registration page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_register' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-register' );
        add_settings_field(
            $this->plugin_name . '-pages-lostpassword',
            __( 'Lost password page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_lostpassword' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-lostpassword' );
        add_settings_field(
            $this->plugin_name . '-pages-resetpassword',
            __( 'Reset password page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_resetpassword' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-resetpassword' );
        add_settings_field(
            $this->plugin_name . '-pages-editprofile',
            __( 'Edit profile page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_editprofile' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-editprofile' );
        add_settings_field(
            $this->plugin_name . '-pages-account',
            __( 'Account page', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_pages_account' ),
            $this->plugin_name,
            $this->plugin_name . '-pages'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-pages-account' );

        add_settings_section(
            $this->plugin_name . '-recaptcha',
            __( 'Google reCAPTCHA', 'happycula-custom-user-pages' ),
            array( $this, 'settings_section_recaptcha' ),
            $this->plugin_name
        );
        add_settings_field(
            $this->plugin_name . '-recaptcha-site-key',
            __( 'Site key', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_recaptcha_site_key' ),
            $this->plugin_name,
            $this->plugin_name . '-recaptcha'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-recaptcha-site-key' );
        add_settings_field(
            $this->plugin_name . '-recaptcha-secret-key',
            __( 'Secret key', 'happycula-custom-user-pages' ),
            array( $this, 'settings_field_recaptcha_secret_key' ),
            $this->plugin_name,
            $this->plugin_name . '-recaptcha'
        );
        register_setting( $this->plugin_name, $this->plugin_name . '-recaptcha-secret-key' );

    }

    /**
     * Echo content for Pages section.
     *
     * @since 1.0.0
     */
    public function settings_section_pages() {

        echo '<p>' . esc_html( 'Select custom pages to use.', 'happycula-custom-user-pages' ) . '</p>';

    }

    /**
     * Echo content for reCaptcha section.
     *
     * @since 1.0.0
     */
    public function settings_section_recaptcha() {

        echo '<p>' . __( 'Enter your Google reCAPTCHA keys (optional). At the moment only <a href="https://developers.google.com/recaptcha/docs/display" target="_blank">reCAPTCHA v2 checkbox</a> is supported.', 'happycula-custom-user-pages' ) . '</p>';

    }

    /**
     * Echo content for login page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_login() {

        $this->dropdown_pages( $this->plugin_name . '-pages-login' );

    }

    /**
     * Echo content for after login page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_after_login() {

        $this->dropdown_pages( $this->plugin_name . '-pages-after-login' );

    }

    /**
     * Echo content for after logout page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_after_logout() {

        $this->dropdown_pages( $this->plugin_name . '-pages-after-logout' );

    }

    /**
     * Echo content for register page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_register() {

        $this->dropdown_pages( $this->plugin_name . '-pages-register' );

    }

    /**
     * Echo content for lost password page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_lostpassword() {

        $this->dropdown_pages( $this->plugin_name . '-pages-lostpassword' );

    }

    /**
     * Echo content for reset password page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_resetpassword() {

        $this->dropdown_pages( $this->plugin_name . '-pages-resetpassword' );

    }

    /**
     * Echo content for edit profile page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_editprofile() {

        $this->dropdown_pages( $this->plugin_name . '-pages-editprofile' );

    }

    /**
     * Echo content for account page field.
     *
     * @since 1.0.0
     */
    public function settings_field_pages_account() {

        $this->dropdown_pages( $this->plugin_name . '-pages-account' );

    }

    /**
     * Echo content for reCaptcha site key field.
     *
     * @since 1.0.0
     */
    public function settings_field_recaptcha_site_key() {

        $value = get_option( $this->plugin_name . '-recaptcha-site-key', '' );
        echo '<input type="text" id="' . esc_attr( $this->plugin_name ) . '-recaptcha-site-key" name="' . esc_attr( $this->plugin_name ) . '-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';

    }

    /**
     * Echo content for reCaptcha secret key field.
     *
     * @since 1.0.0
     */
    public function settings_field_recaptcha_secret_key() {

        $value = get_option( $this->plugin_name . '-recaptcha-secret-key', '' );
        echo '<input type="text" id="' . esc_attr( $this->plugin_name ) . '-recaptcha-secret-key" name="' . esc_attr( $this->plugin_name ) . '-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';

    }

    /**
     * Echo list of pages with selected option.
     *
     * @since  1.0.0
     * @access private
     *
     * @param  string $option Option name.
     */
    private function dropdown_pages( $option ) {

        $args = array(
            'depth'                 => 0,
            'child_of'              => 0,
            'selected'              => get_option( $option ),
            'echo'                  => 1,
            'name'                  => $option,
            'class'                 => '',
            'show_option_none'      => '',
            'show_option_no_change' => '',
        );
        wp_dropdown_pages( $args );

    }

}
