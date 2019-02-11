<?php
/**
 * Already logged in content.
 *
 * This content is rendered when a user tries to access the login, register,
 * lostpassword or resetpassword form while already logged in.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

$username = wp_get_current_user()->display_name;
?>

<div class="hcup_container">
    <p><?php esc_html_e( 'You are already signed in.', 'happycula-custom-user-pages' ); ?></p>
    <p>
        <?php esc_html_e( 'Where do you want to go next?', 'happycula-custom-user-pages' ); ?><br/>
        <a href="<?php echo esc_attr( hcup_page_url( 'account' ) ); ?>"><?php esc_html_e( 'Go to my account', 'happycula-custom-user-pages' ); ?></a><br/>
        <a href="<?php echo esc_attr( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to homepage', 'happycula-custom-user-pages' ); ?></a><br/>
        <?php if ( current_user_can( 'edit_posts' ) ) : ?>
            <a href="<?php echo esc_attr( admin_url() ); ?>"><?php esc_html_e( 'Go to admin', 'happycula-custom-user-pages' ); ?>
        <?php endif; ?>
    </p>
    <?php // translators: 1. Username 2. Logout url. ?>
    <p><?php echo sprintf( __( 'Not %1$s? <a href="%2$s">Sign out</a>', 'happycula-custom-user-pages' ), esc_html( $username ), esc_attr( wp_logout_url() ) ); ?></p>
</div>
