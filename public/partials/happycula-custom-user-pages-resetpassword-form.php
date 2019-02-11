<?php
/**
 * Custom password reset form.
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

	<form action="<?php echo esc_attr( site_url( 'wp-login.php?action=resetpass' ) ); ?>" method="post" autocomplete="off">
		<input type="hidden" name="login" value="<?php echo esc_attr( $vars['login'] ); ?>" autocomplete="off" />
		<input type="hidden" name="key" value="<?php echo esc_attr( $vars['key'] ); ?>" />
		<p>
			<label for="password1"><?php esc_html_e( 'New password', 'happycula-custom-user-pages' ); ?></label>
			<input type="password" name="password1" required autocomplete="off" />
		</p>
		<p>
			<label for="password2"><?php esc_html_e( 'Confirm password', 'happycula-custom-user-pages' ); ?></label>
			<input type="password" name="password2" required autocomplete="off" />
		</p>
		<p class="hcup_reset_hint"><?php echo esc_html( wp_get_password_hint() ); ?></p>
		<p class="hcup_submit"><input type="submit" value="<?php echo esc_attr( __( 'Reset password', 'happycula-custom-user-pages' ) ); ?>" /></p>
	</form>
</div>
