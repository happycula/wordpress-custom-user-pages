<?php
/**
 * Custom lost password form.
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

    <p><?php esc_html_e( 'Enter your email address and we\'ll send you a link to pick a new password.', 'happycula-custom-user-pages' ); ?></p>

    <form action="<?php echo esc_attr( wp_lostpassword_url() ); ?>" method="post" autocomplete="off">
        <p>
            <label for="user_login"><?php esc_html_e( 'Email', 'happycula-custom-user-pages' ); ?></label>
            <input type="text" name="user_login" required autocomplete="off" />
        </p>
        <p class="hcup_submit"><input type="submit" value="<?php echo esc_attr( __( 'Reset password', 'happycula-custom-user-pages' ) ); ?>" /></p>
    </form>
</div>
