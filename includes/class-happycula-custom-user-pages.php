<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/includes
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Happycula Custom User Pages class.
 */
class Happycula_Custom_User_Pages {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    Happycula_Custom_User_Pages_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct() {

        if ( defined( 'HAPPYCULA_CUSTOM_USER_PAGES_VERSION' ) ) {
            $this->version = HAPPYCULA_CUSTOM_USER_PAGES_VERSION;
        } else {
            $this->version = '1.0.4';
        }
        if ( defined( 'HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME' ) ) {
            $this->plugin_name = HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME;
        } else {
            $this->plugin_name = 'happycula-custom-user-pages';
        }

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Happycula_Custom_User_Pages_Loader. Orchestrates the hooks of the plugin.
     * - Happycula_Custom_User_Pages_I18n. Defines internationalization functionality.
     * - Happycula_Custom_User_Pages_Admin. Defines all hooks for the admin area.
     * - Happycula_Custom_User_Pages_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function load_dependencies() {

        $dir = plugin_dir_path( dirname( __FILE__ ) );

        /**
         * The class responsible for orchestrating the actions and filters.
         */
        require_once $dir . 'includes/class-happycula-custom-user-pages-loader.php';

        /**
         * The class responsible for defining internationalization functionality.
         */
        require_once $dir . 'includes/class-happycula-custom-user-pages-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once $dir . 'admin/class-happycula-custom-user-pages-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing side of the site.
         */
        require_once $dir . 'public/class-happycula-custom-user-pages-public.php';

        $this->loader = new Happycula_Custom_User_Pages_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Happycula_Custom_User_Pages_I18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function set_locale() {

        $plugin_i18n = new Happycula_Custom_User_Pages_I18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_admin_hooks() {

        if ( is_admin() ) {
            $plugin_admin = new Happycula_Custom_User_Pages_Admin( $this->get_plugin_name(), $this->get_version() );

            $filter_links = 'plugin_action_links_' . $this->plugin_name . '/' . $this->plugin_name . '.php';
            $this->loader->add_filter( $filter_links, $plugin_admin, 'add_action_links' );

            $this->loader->add_action( 'admin_init', $plugin_admin, 'maybe_redirect' );
            $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_custom_options_page' );
            $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        }

    }

    /**
     * Register all of the hooks related to the public-facing functionality of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_public_hooks() {

        if ( ! is_admin() ) {
            $plugin_public = new Happycula_Custom_User_Pages_Public( $this->get_plugin_name(), $this->get_version() );

            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
            $this->loader->add_action( 'init', $plugin_public, 'disable_adminbar' );
            $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

            // Login / logout.
            $this->loader->add_action( 'login_form_login', $plugin_public, 'redirect_to_login' );
            $this->loader->add_filter( 'authenticate', $plugin_public, 'maybe_redirect_at_authenticate', 101, 3 );
            $this->loader->add_filter( 'login_redirect', $plugin_public, 'redirect_after_login', 10, 3 );
            $this->loader->add_action( 'wp_logout', $plugin_public, 'redirect_after_logout' );
            // Register.
            $this->loader->add_action( 'login_form_register', $plugin_public, 'redirect_to_register' );
            $this->loader->add_action( 'login_form_register', $plugin_public, 'do_register_user' );
            // Reset password.
            $this->loader->add_action( 'login_form_rp', $plugin_public, 'redirect_to_resetpassword' );
            $this->loader->add_action( 'login_form_rp', $plugin_public, 'do_reset_password' );
            $this->loader->add_action( 'login_form_resetpass', $plugin_public, 'redirect_to_resetpassword' );
            $this->loader->add_action( 'login_form_resetpass', $plugin_public, 'do_reset_password' );
            // Lost password.
            $this->loader->add_action( 'login_form_lostpassword', $plugin_public, 'redirect_to_lostpassword' );
            $this->loader->add_action( 'login_form_lostpassword', $plugin_public, 'do_lost_password' );
            $this->loader->add_filter( 'retrieve_password_message', $plugin_public, 'lostpassword_email', 10, 4 );
            // Edit profile.
            $this->loader->add_action( 'init', $plugin_public, 'do_edit_profile' );
            $this->loader->add_filter( 'password_change_email', $plugin_public, 'editprofile_email', 10, 3 );
        }

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run() {

        $this->loader->run();

    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string The name of the plugin.
     */
    public function get_plugin_name() {

        return $this->plugin_name;

    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.0.0
     * @return Happycula_Custom_User_Pages_Loader Orchestrates the hooks of the plugin.
     */
    public function get_loader() {

        return $this->loader;

    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.0.0
     * @return string The version number of the plugin.
     */
    public function get_version() {

        return $this->version;

    }

}
