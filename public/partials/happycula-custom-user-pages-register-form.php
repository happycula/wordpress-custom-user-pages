<?php
/**
 * Custom register form.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

?>

<div class="hcup_container">
    <?php if ( isset( $vars['errors'] ) && count( $vars['errors'] ) ) : ?>
    <ul class="hcup_errors">
        <?php foreach ( $vars['errors'] as $hcup_error ) : ?>
            <li><?php echo $hcup_error; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form action="<?php echo esc_attr( wp_registration_url() ); ?>" method="post">
        <p>
            <label for="user_email"><?php esc_html_e( 'Email', 'happycula-custom-user-pages' ); ?></label>
            <input type="email" name="user_email" required value="<?php echo esc_attr( $vars['email'] ); ?>" />
        </p>
        <p>
            <label for="user_firstname"><?php esc_html_e( 'Firstname', 'happycula-custom-user-pages' ); ?></label>
            <input type="text" name="user_firstname" required value="<?php echo esc_attr( $vars['firstname'] ); ?>" />
        </p>
        <p>
            <label for="user_lastname"><?php esc_html_e( 'Lastname', 'happycula-custom-user-pages' ); ?></label>
            <input type="text" name="user_lastname" required value="<?php echo esc_attr( $vars['lastname'] ); ?>" />
        </p>
        <p class="hcup_register_info"><?php esc_html_e( 'Registration confirmation will be emailed to you.', 'happycula-custom-user-pages' ); ?></p>


        <?php if ( isset( $vars['recaptcha_site_key'] ) && $vars['recaptcha_site_key'] ) : ?>
        <div class="hcup_recaptcha_container">
            <div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $vars['recaptcha_site_key'] ); ?>"></div>
        </div>
        <?php endif; ?>

        <p class="hcup_submit"><input type="submit" value="<?php echo esc_attr( __( 'Register', 'happycula-custom-user-pages' ) ); ?>" /></p>
    </form>

    <a href="<?php echo esc_attr( hcup_page_url( 'login' ) ); ?>"><?php esc_html_e( 'Sign in', 'happycula-custom-user-pages' ); ?></a><br/>
    <a href="<?php echo esc_attr( hcup_page_url( 'lostpassword' ) ); ?>"><?php esc_html_e( 'Forgot your password?', 'happycula-custom-user-pages' ); ?></a>
</div>
