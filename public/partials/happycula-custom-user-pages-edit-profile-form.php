<?php
/**
 * Custom edit profile form.
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

    <form action="<?php echo esc_attr( hcup_page_url( 'editprofile' ) ); ?>" method="post">
        <?php wp_nonce_field( 'update-user_' . $vars['user']->ID ); ?>
        <?php if ( isset( $wp_http_referer ) && $wp_http_referer ) : ?>
            <input type="hidden" name="wp_http_referer" value="<?php echo esc_url( $wp_http_referer ); ?>" />
        <?php endif; ?>
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="checkuser_id" value="<?php echo esc_attr( $vars['user']->ID ); ?>" />

        <p>
            <label for="first_name"><?php esc_html_e( 'Firstname', 'happycula-custom-user-pages' ); ?></label>
            <input type="text" name="first_name" id="first_name" required value="<?php echo esc_attr( $vars['user']->first_name ); ?>" />
        </p>
        <p>
            <label for="last_name"><?php esc_html_e( 'Lastname', 'happycula-custom-user-pages' ); ?></label>
            <input type="text" name="last_name" id="last_name" required value="<?php echo esc_attr( $vars['user']->last_name ); ?>" />
        </p>
        <p>
            <label for="pass1"><?php esc_html_e( 'New password', 'happycula-custom-user-pages' ); ?></label>
            <input type="password" name="pass1" id="pass1" autocomplete="off" value="" />
        </p>
        <div id="hcup_pass_strength"></div>
        <p>
            <label for="pass2"><?php esc_html_e( 'Confirm your new password', 'happycula-custom-user-pages' ); ?></label>
            <input type="password" name="pass2" id="pass2" autocomplete="off" value="" />
        </p>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $vars['user']->ID ); ?>" />

        <p class="hcup_submit"><input type="submit" value="<?php echo esc_attr_e( 'Update profile', 'happycula-custom-user-pages' ); ?>" /></p>
    </form>
</div>
