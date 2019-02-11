<?php
/**
 * Account page content.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/public/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

?>

<div class="hcup_container">
    <a href="<?php echo esc_attr( hcup_page_url( 'editprofile' ) ); ?>"><?php esc_html_e( 'Edit my profile', 'happycula-custom-user-pages' ); ?></a><br/>
    <a href="<?php echo esc_attr( wp_logout_url() ); ?>"><?php esc_html_e( 'Sign out', 'happycula-custom-user-pages' ); ?></a>
</div>
