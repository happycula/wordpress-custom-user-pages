<?php
/**
 * Custom password changed email.
 *
 * The following strings have a special meaning and will get replaced dynamically:
 *   - ###USERNAME###    The current user's username.
 *   - ###ADMIN_EMAIL### The admin email in case this was unexpected.
 *   - ###EMAIL###       The user's email address.
 *   - ###SITENAME###    The name of the site.
 *   - ###SITEURL###     The URL to the site.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

?>
<?php _e( 'Hello ###USERNAME###!', 'happycula-custom-user-pages' ); ?>


<?php _e( 'Your password was changed on ###SITENAME###.', 'happycula-custom-user-pages' ); ?>

<?php _e( 'If you did not change your password, please contact the site administrator at ###ADMIN_EMAIL### as soon as possible.', 'happycula-custom-user-pages' ); ?>


<?php _e( 'See you soon!', 'happycula-custom-user-pages' ); ?>
<?php
