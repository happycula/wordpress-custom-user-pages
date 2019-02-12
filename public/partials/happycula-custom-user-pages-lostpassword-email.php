<?php
/**
 * Custom lost password email.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

?>
<?php // translators: 1. User first name. ?>
<?php echo sprintf( __( 'Hello %s!', 'happycula-custom-user-pages' ), $vars['user']->first_name ); ?>


<?php // translators: 1. Site name. ?>
<?php echo sprintf( __( 'We received a request to reset your password on our site "%s".', 'happycula-custom-user-pages' ), get_bloginfo( 'name' ) ); ?>

<?php _e( 'If this was a mistake or you didn\'t ask for a password reset, just ignore this email and nothing will happen.', 'happycula-custom-user-pages' ); ?>


<?php _e( 'To reset your password, visit the following link:', 'happycula-custom-user-pages' ); ?>

<?php echo site_url( 'wp-login.php?action=rp&key=' . $vars['key'] . '&login=' . rawurlencode( $vars['login'] ) ); ?>


<?php _e( 'See you soon!', 'happycula-custom-user-pages' ); ?>
<?php
