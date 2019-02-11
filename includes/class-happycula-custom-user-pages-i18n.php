<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dev.happycula.fr/wordpress/custom-user-pages
 * @since      1.0.0
 * @package    Happycula_Custom_User_Pages
 * @subpackage Happycula_Custom_User_Pages/includes
 * @author     Happycula <yann+wordpress@happycula.fr>
 */

/**
 * Happycula Custom User Pages I18n class.
 */
class Happycula_Custom_User_Pages_I18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'happycula-custom-user-pages',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}
