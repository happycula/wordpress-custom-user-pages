<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Happycula Custom User Pages Public class.
 */
class Happycula_Custom_User_Pages_Public {

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
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts() {

        $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

        if ( false !== strpos( $request_uri, $this->get_option_page_url( 'register', true ) ) ) {
            wp_enqueue_script( 'hcup-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), '20190211', true );
        }
        if ( false !== strpos( $request_uri, $this->get_option_page_url( 'editprofile', true ) ) ) {
            wp_enqueue_script( 'password-strength-meter' );
            $hpc_js = plugin_dir_url( dirname( __FILE__ ) ) . '/public/js/' . $this->plugin_name . '-password_check.js';
            wp_enqueue_script( 'hcup-password-check', $hpc_js, array( 'password-strength-meter' ), '20190211', true );
        }

    }

    /**
     * Disable admin toolbar for users with no access to admin.
     *
     * @since 1.0.0
     */
    public function disable_adminbar() {

        if ( ! current_user_can( 'edit_posts' ) ) {
            show_admin_bar( false );
        }

    }

    /**
     * Redirect the user to the custom login page instead of wp-login.php.
     *
     * @since 1.0.0
     */
    public function redirect_to_login() {

        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

        if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
            $this->redirect_logged_in_user( $redirect_to );

            $login_url = $this->get_option_page_url( 'login' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }
            wp_safe_redirect( $login_url );
            exit;
        }

    }

    /**
     * Redirect the user after authentication if there were any errors.
     *
     * @since  1.0.0
     * @param  Wp_User|Wp_Error $user     The signed in user, or the errors that have occurred during login.
     * @param  string           $username The user name used to log in.
     * @param  string           $password The password used to log in.
     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
     */
    public function maybe_redirect_at_authenticate( $user, $username, $password ) {

        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );
                $login_url   = $this->get_option_page_url( 'login' );
                $login_url   = add_query_arg( 'login', $error_codes, $login_url );
                $login_url   = add_query_arg( 'username', $username, $login_url );
                wp_safe_redirect( $login_url );
                exit;
            }
        }

        return $user;

    }

    /**
     * Returns the URL to which the user should be redirected after the (successful) login.
     *
     * @since 1.0.0
     * @param string           $redirect_to           The redirect destination URL.
     * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User|WP_Error $user                  The logged in user, or error information if there were errors.
     *
     * @return string Redirect URL
     */
    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

        if ( ! isset( $user->ID ) ) {
            return home_url();
        }

        if ( '' === $requested_redirect_to ) {
            if ( user_can( $user, 'edit_posts' ) ) {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $this->get_option_page_url( 'after-login' );
            }
        } else {
            $redirect_url = $redirect_to;
        }

        return wp_validate_redirect( $redirect_url, home_url() );

    }

    /**
     * Redirect the user to the custom page after it logs out.
     *
     * @since 1.0.0
     */
    public function redirect_after_logout() {

        $logout_url = $this->get_option_page_url( 'after-logout' );

        if ( $logout_url ) {
            wp_safe_redirect( $logout_url );
            exit;
        }

    }

    /**
     * Redirect the user to the custom register page instead of wp-login.php?action=register.
     *
     * @since 1.0.0
     */
    public function redirect_to_register() {

        if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
            $this->redirect_logged_in_user();

            $register_url = $this->get_option_page_url( 'register' );
            wp_safe_redirect( $register_url );
            exit;
        }

    }

    /**
     * Handles the registration of a new user.
     *
     * Used through the action hook "login_form_register" activated on wp-login.php
     * when accessed through the registration action.
     *
     * @since 1.0.0
     */
    public function do_register_user() {

        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            $email      = $_POST['user_email'];
            $first_name = sanitize_text_field( $_POST['user_firstname'] );
            $last_name  = sanitize_text_field( $_POST['user_lastname'] );

            $redirect_url = $this->get_option_page_url( 'register' );
            $redirect_url = add_query_arg( 'email', $email, $redirect_url );
            $redirect_url = add_query_arg( 'lastname', $last_name, $redirect_url );
            $redirect_url = add_query_arg( 'firstname', $first_name, $redirect_url );

            if ( ! get_option( 'users_can_register' ) ) {
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } elseif ( ! $this->verify_recaptcha() ) {
                $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
            } else {
                $result = $this->register_user( $email, $first_name, $last_name );
                if ( is_wp_error( $result ) ) {
                    $errors       = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else {
                    $redirect_url = $this->get_option_page_url( 'login' );
                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
                }
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }

    }

    /**
     * Redirect the user to the custom reset password page instead of wp-login.php?action=reset.
     *
     * @since 1.0.0
     */
    public function redirect_to_resetpassword() {

        if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
            $this->redirect_logged_in_user();

            $redirect_url = $this->get_option_page_url( 'resetpassword' );
            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            if ( ! $user || is_wp_error( $user ) ) {
                $redirect_url = $this->get_option_page_url( 'login' );
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    $redirect_url = add_query_arg( 'login', 'resetpassword_expiredkey', $redirect_url );
                } else {
                    $redirect_url = add_query_arg( 'login', 'resetpassword_invalidkey', $redirect_url );
                }
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }

    }

    /**
     * Resets the user's password.
     *
     * @since 1.0.0
     */
    public function do_reset_password() {

        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            $key   = $_POST['key'];
            $login = $_POST['login'];

            $user = check_password_reset_key( $key, $login );
            if ( ! $user || is_wp_error( $user ) ) {
                $redirect_url = $this->get_option_page_url( 'login' );
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    $redirect_url = add_query_arg( 'login', 'resetpassword_expiredkey', $redirect_url );
                } else {
                    $redirect_url = add_query_arg( 'login', 'resetpassword_invalidkey', $redirect_url );
                }
                wp_safe_redirect( $redirect_url );
                exit;
            }

            $redirect_url = $this->get_option_page_url( 'resetpassword' );
            $redirect_url = add_query_arg( 'key', $key, $redirect_url );
            $redirect_url = add_query_arg( 'login', $login, $redirect_url );
            if ( ! isset( $_POST['password1'] ) || ! isset( $_POST['password2'] ) ) {
                $redirect_url = add_query_arg( 'error', 'unknown', $redirect_url );
            } else {
                if ( empty( $_POST['password1'] ) ) {
                    $redirect_url = add_query_arg( 'error', 'resetpassword_empty', $redirect_url );
                } elseif ( $_POST['password1'] !== $_POST['password2'] ) {
                    $redirect_url = add_query_arg( 'error', 'resetpassword_mismatch', $redirect_url );
                } else {
                    reset_password( $user, $_POST['password1'] );
                    $redirect_url = $this->get_option_page_url( 'login' );
                    $redirect_url = add_query_arg( 'password', 'changed', $redirect_url );
                }
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }

    }

    /**
     * Redirect the user to the custom lost password page instead of wp-login.php?action=lostpassword.
     *
     * @since 1.0.0
     */
    public function redirect_to_lostpassword() {

        if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
            $this->redirect_logged_in_user();

            $redirect_url = $this->get_option_page_url( 'lostpassword' );
            wp_safe_redirect( $redirect_url );
            exit;
        }

    }

    /**
     * Resets the user's password.
     *
     * @since 1.0.0
     */
    public function do_lost_password() {

        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            $errors = retrieve_password();

            if ( is_wp_error( $errors ) ) {
                $errors       = join( ',', $errors->get_error_codes() );
                $redirect_url = $this->get_option_page_url( 'lostpassword' );
                $redirect_url = add_query_arg( 'errors', $errors, $redirect_url );
            } else {
                $redirect_url = $this->get_option_page_url( 'login' );
                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                if ( ! empty( $_REQUEST['redirect_to'] ) ) {
                    $redirect_url = $_REQUEST['redirect_to'];
                }
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }

    }

    /**
     * Returns the content of the email sent for password reset.
     *
     * @since  1.0.0
     * @param  string  $message Default mail message.
     * @param  string  $key     Activation key.
     * @param  string  $login   Username.
     * @param  WP_User $user    WP_User object.
     * @return string  The message to send.
     */
    public function lostpassword_email( $message, $key, $login, $user ) {

        $attributes = array(
            'message' => $message,
            'key'     => $key,
            'login'   => $login,
            'user'    => $user,
        );

        return $this->get_template_html( 'lostpassword-email', $attributes );

    }

    /**
     * Update user's informations.
     *
     * @since 1.0.0
     */
    public function do_edit_profile() {

        if ( $_SERVER['REQUEST_URI'] === $this->get_option_page_url( 'editprofile', true ) ) {
            if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
                $errors = new WP_Error();

                $_POST['first_name'] = ucfirst( trim( $_POST['first_name'] ) );
                if ( empty( $_POST['first_name'] ) ) {
                    $errors->add( 'firstname_missing', $this->get_error_message( 'firstname_missing' ) );
                }
                $_POST['last_name'] = ucfirst( trim( $_POST['last_name'] ) );
                if ( empty( $_POST['last_name'] ) ) {
                    $errors->add( 'lastname_missing', $this->get_error_message( 'lastname_missing' ) );
                }

                if ( count( $errors->get_error_codes() ) === 0 ) {
                    if ( ! function_exists( 'edit_user' ) ) {
                        require_once ABSPATH . '/wp-admin/includes/user.php';
                    }

                    $user                  = wp_get_current_user();
                    $_POST['email']        = $user->user_email;
                    $_POST['nickname']     = $_POST['first_name'] . ' ' . substr( $_POST['last_name'], 0, 1 ) . '.';
                    $_POST['display_name'] = $_POST['nickname'];

                    $errors = edit_user( $user->ID );
                    if ( is_wp_error( $errors ) ) {
                        $this->editprofile_errors = $errors->get_error_messages();
                    }
                } else {
                    $this->editprofile_errors = $errors->get_error_messages();
                }
            }
        }

    }

    /**
     * Returns the content of the email sent for password reset.
     *
     * @since  1.0.0
     * @param  array $pass_change_email {
     *            Used to build wp_mail().
     *            @type string $to      The intended recipients. Add emails in a comma separated string.
     *            @type string $subject The subject of the email.
     *            @type string $message The content of the email.
     *                The following strings have a special meaning and will get replaced dynamically:
     *                - ###USERNAME###    The current user's username.
     *                - ###ADMIN_EMAIL### The admin email in case this was unexpected.
     *                - ###EMAIL###       The user's email address.
     *                - ###SITENAME###    The name of the site.
     *                - ###SITEURL###     The URL to the site.
     *            @type string $headers Headers. Add headers in a newline (\r\n) separated string.
     *         }
     * @param  array $user     The original user array.
     * @param  array $userdata The updated user array.
     * @return array $pass_change_email updated.
     */
    public function editprofile_email( $pass_change_email, $user, $userdata ) {

        $pass_change_email['message'] = $this->get_template_html( 'password-changed-email', array() );

        return $pass_change_email;

    }

    /**
     * Register shortcodes.
     *
     * @since 1.0.0
     */
    public function register_shortcodes() {

        add_shortcode( 'hcup_login_form', array( $this, 'shortcode_login_form' ) );
        add_shortcode( 'hcup_register_form', array( $this, 'shortcode_register_form' ) );
        add_shortcode( 'hcup_resetpassword_form', array( $this, 'shortcode_resetpassword_form' ) );
        add_shortcode( 'hcup_lostpassword_form', array( $this, 'shortcode_lostpassword_form' ) );
        add_shortcode( 'hcup_editprofile_form', array( $this, 'shortcode_editprofile_form' ) );
        add_shortcode( 'hcup_account', array( $this, 'shortcode_account' ) );

    }

    /**
     * Display custom login form.
     *
     * @since  1.0.0
     * @param  array  $attributes Shortcode attributes.
     * @param  string $content    The text content for shortcode. Not used.
     * @return string
     */
    public function shortcode_login_form( $attributes, $content = null ) {

        $attributes = shortcode_atts( array(), $attributes );

        if ( is_user_logged_in() ) {
            return $this->get_template_html( 'logged-in', $attributes );
        }

        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'] );
        }

        $errors = array();
        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );
            foreach ( $error_codes as $code ) {
                $errors[] = $this->get_error_message( $code );
            }
        }
        $attributes['errors']   = $errors;
        $attributes['username'] = isset( $_REQUEST['username'] ) ? $_REQUEST['username'] : '';
        if ( isset( $_REQUEST['registered'] ) ) {
            $attributes['registered'] = true;
            $attributes['username']   = $_REQUEST['registered'];
        }
        if ( isset( $_REQUEST['checkemail'] ) && 'confirm' === $_REQUEST['checkemail'] ) {
            $attributes['lostpassword_sent'] = true;
        }
        if ( isset( $_REQUEST['password'] ) && 'changed' === $_REQUEST['password'] ) {
            $attributes['password_reset'] = true;
        }

        return $this->get_template_html( 'login-form', $attributes );

    }

    /**
     * Display custom register form.
     *
     * @since  1.0.0
     * @param  array  $attributes Shortcode attributes.
     * @param  string $content    The text content for shortcode. Not used.
     * @return string
     */
    public function shortcode_register_form( $attributes, $content = null ) {

        $attributes = shortcode_atts( array(), $attributes );

        if ( ! get_option( 'users_can_register' ) ) {
            return $this->get_template_html( 'register-disable', $attributes );
        }

        if ( is_user_logged_in() ) {
            return $this->get_template_html( 'logged-in', $attributes );
        }

        $errors = array();
        if ( isset( $_REQUEST['register-errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['register-errors'] );
            foreach ( $error_codes as $code ) {
                $errors[] = $this->get_error_message( $code );
            }
        }
        $attributes['errors']             = $errors;
        $attributes['recaptcha_site_key'] = get_option( $this->plugin_name . '-recaptcha-site-key', null );
        $attributes['email']              = isset( $_REQUEST['email'] ) ? $_REQUEST['email'] : '';
        $attributes['firstname']          = isset( $_REQUEST['firstname'] ) ? $_REQUEST['firstname'] : '';
        $attributes['lastname']           = isset( $_REQUEST['lastname'] ) ? $_REQUEST['lastname'] : '';

        return $this->get_template_html( 'register-form', $attributes );

    }

    /**
     * Display custom password reset form.
     *
     * @since  1.0.0
     * @param  array  $attributes Shortcode attributes.
     * @param  string $content    The text content for shortcode. Not used.
     * @return string
     */
    public function shortcode_resetpassword_form( $attributes, $content = null ) {

        $attributes = shortcode_atts( array(), $attributes );

        if ( is_user_logged_in() ) {
            return $this->get_template_html( 'logged-in', $attributes );
        }

        if ( ! isset( $_REQUEST['login'] ) || ! isset( $_REQUEST['key'] ) ) {
            $attributes['errors'][] = $this->get_error_message( 'resetpassword_invalidkey' );
            return $this->get_template_html( 'login-form', $attributes );
        }

        $errors = array();
        if ( isset( $_REQUEST['error'] ) ) {
            $error_codes = explode( ',', $_REQUEST['error'] );
            foreach ( $error_codes as $code ) {
                $errors[] = $this->get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;
        $attributes['login']  = $_REQUEST['login'];
        $attributes['key']    = $_REQUEST['key'];

        return $this->get_template_html( 'resetpassword-form', $attributes );

    }

    /**
     * Display custom lost password form.
     *
     * @since  1.0.0
     * @param  array  $attributes Shortcode attributes.
     * @param  string $content    The text content for shortcode. Not used.
     * @return string
     */
    public function shortcode_lostpassword_form( $attributes, $content = null ) {

        $attributes = shortcode_atts( array(), $attributes );

        if ( is_user_logged_in() ) {
            return $this->get_template_html( 'logged-in', $attributes );
        }

        $errors = array();
        if ( isset( $_REQUEST['errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['errors'] );
            foreach ( $error_codes as $code ) {
                $errors[] = $this->get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;

        return $this->get_template_html( 'lostpassword-form', $attributes );

    }

    /**
     * Display custom edit profile page.
     *
     * @since  1.0.0
     * @param  array  $attributes Shortcode attributes.
     * @param  string $content    The text content for shortcode. Not used.
     * @return string
     */
    public function shortcode_editprofile_form( $attributes, $content = null ) {

        $attributes                      = shortcode_atts( array(), $attributes );
        $login_attributes                = $attributes;
        $login_attributes['redirect_to'] = $this->get_option_page_url( 'editprofile' );

        if ( ! is_user_logged_in() ) {
            return $this->shortcode_login_form( $login_attributes, $content );
        }

        $attributes['user'] = wp_get_current_user();
        if ( isset( $this->editprofile_errors ) ) {
            $attributes['errors'] = $this->editprofile_errors;
        }

        return $this->get_template_html( 'edit-profile-form', $attributes );

    }

    /**
     * Display custom account page.
     *
     * @since  1.0.0
     * @param  array  $attributes Shortcode attributes.
     * @param  string $content    The text content for shortcode. Not used.
     * @return string
     */
    public function shortcode_account( $attributes, $content = null ) {

        $attributes                      = shortcode_atts( array(), $attributes );
        $login_attributes                = $attributes;
        $login_attributes['redirect_to'] = $this->get_option_page_url( 'account' );

        return is_user_logged_in() ?
                $this->get_template_html( 'account', $attributes ) :
                $this->shortcode_login_form( $login_attributes, $content );

    }

    /**
     * Redirect users to account page or admin based on its role.
     *
     * @since  1.0.0
     * @access private
     * @param  string $redirect_to An optional URL for admin users.
     */
    private function redirect_logged_in_user( $redirect_to = null ) {

        if ( is_user_logged_in() ) {
            $url = $this->get_option_page_url( 'account' );
            if ( current_user_can( 'edit_posts' ) ) {
                if ( $redirect_to ) {
                    wp_safe_redirect( $redirect_to );
                    exit;
                }
                $url = admin_url();
            }

            if ( $url ) {
                wp_safe_redirect( $url );
                exit;
            }

            wp_safe_redirect( home_url( '/' ) );
            exit;
        }

    }

    /**
     * Return the url of a custom page.
     *
     * @since  1.0.0
     * @access private
     * @param  string  $page     Name of the page.
     * @param  boolean $relative Should the returned url be relative. Default: false.
     * @return string|false
     */
    private function get_option_page_url( $page, $relative = false ) {

        $url = get_permalink( get_option( $this->plugin_name . '-pages-' . $page, 0 ) );
        if ( $relative ) {
            $url = str_replace( home_url(), '', $url );
        }

        return $url;
    }

    /**
     * Renders the contents of the given template to a string and returns it.
     *
     * Looks for template in the current theme (child then parent) or fallbacks to default one.
     *
     * @since  1.0.0
     * @access private
     * @param  string $template_name The name of the template to render (without .php).
     * @param  array  $vars          The PHP variables for the template.
     * @return string
     */
    private function get_template_html( $template_name, $vars = null ) {

        if ( ! $vars ) {
            $vars = array();
        }

        $files = array(
            get_stylesheet_directory() . '/happycula-custom-user-pages/' . $template_name . '.php',
            get_template_directory() . '/happycula-custom-user-pages/' . $template_name . '.php',
            dirname( __FILE__ ) . '/partials/happycula-custom-user-pages-' . $template_name . '.php',
        );

        /* translators: 1: Template name */
        $html = sprintf( __( 'No "%s" template found.', 'happycula-custom-user-pages' ), $template_name );

        foreach ( $files as $file ) {
            if ( is_readable( $file ) ) {
                ob_start();
                require $file;
                $html = ob_get_contents();
                ob_end_clean();
                break;
            }
        }

        return $html;

    }

    /**
     * Finds and returns a matching error message for the given error code.
     *
     * @since  1.0.0
     * @access private
     * @param  string $error_code The error code to look up.
     * @return string              An error message.
     */
    private function get_error_message( $error_code ) {

        switch ( $error_code ) {
            case 'empty_username':
                return __( 'You do have an email address, right?', 'happycula-custom-user-pages' );
            case 'empty_password':
                return __( 'You need to enter a password to sign in.', 'happycula-custom-user-pages' );
            case 'invalid_username':
            case 'incorrect_password':
                /* translators: 1: URL of lost password page */
                $err = __(
                    "The username and/or password you entered wasn't quite right. <a href='%s'>Did you forget your password?</a>",
                    'happycula-custom-user-pages'
                );
                return sprintf( $err, $this->get_option_page_url( 'lostpassword' ) );
            case 'firstname_missing':
                return __( 'You need to enter your firstname.', 'happycula-custom-user-pages' );
            case 'lastname_missing':
                return __( 'You need to enter your lastname.', 'happycula-custom-user-pages' );
            case 'email':
                return __( 'The email address you entered is not valid.', 'happycula-custom-user-pages' );
            case 'email_exists':
                return __( 'An account already exists with this email address.', 'happycula-custom-user-pages' );
            case 'closed':
                return __( 'Registering new users is currently not allowed.', 'happycula-custom-user-pages' );
            case 'captcha':
                return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'happycula-custom-user-pages' );
            case 'invalid_email':
            case 'invalidcombo':
                return __( 'There are no users registered with this email address.', 'happycula-custom-user-pages' );
            case 'resetpassword_expiredkey':
            case 'resetpassword_invalidkey':
                return __( 'The password reset link you used is not valid anymore.', 'happycula-custom-user-pages' );
            case 'resetpassword_mismatch':
                return __( "The two passwords you entered don't match.", 'happycula-custom-user-pages' );
            case 'resetpassword_empty':
                return __( 'You need to enter a new password.', 'happycula-custom-user-pages' );
            default:
                break;
        }

        return __( 'An unknown error occurred. Please try again later.', 'happycula-custom-user-pages' );

    }

    /**
     * Validates and then completes the new user signup process if all went well.
     *
     * @since  1.0.0
     * @access private
     * @param  string $email      The new user's email address.
     * @param  string $first_name The new user's first name.
     * @param  string $last_name  The new user's last name.
     * @return int|WP_Error             The id of the user that was created, or error if failed.
     */
    private function register_user( $email, $first_name, $last_name ) {

        $errors = new WP_Error();

        if ( ! is_email( $email ) ) {
            $errors->add( 'email', $this->get_error_message( 'email' ) );
        } elseif ( username_exists( $email ) || email_exists( $email ) ) {
            $errors->add( 'email_exists', $this->get_error_message( 'email_exists' ) );
        }
        $first_name = ucfirst( trim( $first_name ) );
        if ( empty( $first_name ) ) {
            $errors->add( 'firstname_missing', $this->get_error_message( 'firstname_missing' ) );
        }
        $last_name = ucfirst( trim( $last_name ) );
        if ( empty( $last_name ) ) {
            $errors->add( 'lastname_missing', $this->get_error_message( 'lastname_missing' ) );
        }

        if ( '' !== $errors->get_error_code() ) {
            return $errors;
        }

        $password  = wp_generate_password( 12, false );
        $user_data = array(
            'user_login'   => $email,
            'user_email'   => $email,
            'user_pass'    => $password,
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'nickname'     => $first_name . ' ' . substr( $last_name, 0, 1 ) . '.',
            'display_name' => $first_name . ' ' . substr( $last_name, 0, 1 ) . '.',
        );
        $user_id   = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, null, 'both' );

        return $user_id;

    }

    /**
     * Checks that the reCaptcha parameter sent with the registration request is valid.
     *
     * @since  1.0.0
     * @access private
     * @return bool    True if the CAPTCHA is OK, otherwise false.
     */
    private function verify_recaptcha() {

        $secret = get_option( $this->plugin_name . '-recaptcha-secret-key', null );
        if ( ! $secret ) {
            return true;
        }

        if ( ! isset( $_POST['g-recaptcha-response'] ) ) {
            return false;
        }

        $response = wp_remote_post(
            'https://www.google.com/recaptcha/api/siteverify',
            array(
                'body' => array(
                    'secret'   => $secret,
                    'response' => $_POST['g-recaptcha-response'],
                ),
            )
        );

        if ( $response && is_array( $response ) ) {
            $decoded_response = json_decode( $response['body'] );
            return $decoded_response->success;
        }

        return false;

    }
}
