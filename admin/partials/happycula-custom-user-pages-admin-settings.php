<?php
/**
 * Options page content.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/admin/partials
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

?>

<div class="wrap">

    <h1><?php esc_html_e( 'Happycula Custom User Pages Settings', 'happycula-custom-user-pages' ); ?></h1>

    <form action="options.php" method="post" novalidate="novalidate">
        <?php
            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
            submit_button();
        ?>
    </form>

</div>
