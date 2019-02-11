<?php
/**
 * Custom login form.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

$args = array(
    'echo'           => true,
    'remember'       => true,
    'value_remember' => false,
    'form_id'        => 'hcup_loginform',
    'value_username' => isset( $vars['username'] ) ? $vars['username'] : '',
    'label_username' => __( 'Username', 'happycula-custom-user-pages' ),
    'label_password' => __( 'Password', 'happycula-custom-user-pages' ),
    'label_remember' => __( 'Remember me', 'happycula-custom-user-pages' ),
    'label_log_in'   => __( 'Sign in', 'happycula-custom-user-pages' ),
);
?>

<div class="hcup_container">
    <?php if ( isset( $vars['errors'] ) && count( $vars['errors'] ) ) : ?>
    <ul class="hcup_errors">
        <?php foreach ( $vars['errors'] as $hcup_error ) : ?>
            <li><?php echo $hcup_error; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php if ( isset( $vars['registered'] ) ) : ?>
    <p class="hcup_login_info"><?php esc_html_e( 'You have successfully registered. We have emailed your password to the email address you provided.', 'happycula-custom-user-pages' ); ?></p>
    <?php endif; ?>

    <?php if ( isset( $vars['lostpassword_sent'] ) ) : ?>
    <p class="hcup_login_info"><?php esc_html_e( 'Check your email for a link to reset your password.', 'happycula-custom-user-pages' ); ?></p>
    <?php endif; ?>

    <?php if ( isset( $vars['password_reset'] ) ) : ?>
    <p class="hcup_login_info"><?php esc_html_e( 'Your password has been updated.', 'happycula-custom-user-pages' ); ?></p>
    <?php endif; ?>

    <?php wp_login_form( $args ); ?>

    <a href="<?php echo esc_attr( hcup_page_url( 'lostpassword' ) ); ?>"><?php esc_html_e( 'Forgot your password?', 'happycula-custom-user-pages' ); ?></a><br/>
    <a href="<?php echo esc_attr( hcup_page_url( 'register' ) ); ?>"><?php esc_html_e( 'Register', 'happycula-custom-user-pages' ); ?></a>
</div>
