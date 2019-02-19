<?php
/**
 * Plugin Name: Happycula Custom User Pages
 * Plugin URI:  https://dev.happycula.fr/wordpress/custom-user-pages
 * Description: Custom user pages (login, registration, profile…)
 * Version:     1.0.2
 * Author:      Happycula
 * Author URI:  https://happycula.fr
 * Text Domain: happycula-custom-user-pages
 * Domain Path: /languages
 * License:     GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Happycula Custom User Pages is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Happycula Custom User Pages is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Happycula Custom User Pages. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 *
 * @since   1.0.0
 * @package Happycula_Custom_User_Pages
 * @author  Happycula <yann+wordpress@happycula.fr>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'HAPPYCULA_CUSTOM_USER_PAGES_VERSION', '1.0.2' );
define( 'HAPPYCULA_CUSTOM_USER_PAGES_PLUGIN_NAME', 'happycula-custom-user-pages' );
define( 'HAPPYCULA_CUSTOM_USER_PAGES_DOMAIN', 'happycula-custom-user-pages' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-happycula-custom-user-pages-activator.php
 */
function activate_happycula_custom_user_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-happycula-custom-user-pages-activator.php';
	Happycula_Custom_User_Pages_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_happycula_custom_user_pages' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-happycula-custom-user-pages-deactivator.php
 */
function deactivate_happycula_custom_user_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-happycula-custom-user-pages-deactivator.php';
	Happycula_Custom_User_Pages_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_happycula_custom_user_pages' );

/**
 * The code that runs during plugin uninstallation.
 * This action is documented in includes/class-happycula-custom-user-pages-uninstaller.php
 */
function uninstall_happycula_custom_user_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-happycula-custom-user-pages-uninstaller.php';
	Happycula_Custom_User_Pages_Uninstaller::uninstall();
}
register_uninstall_hook( __FILE__, 'uninstall_happycula_custom_user_pages' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-happycula-custom-user-pages.php';

/**
 * Utility functions for theme development.
 */
require plugin_dir_path( __FILE__ ) . 'includes/happycula-custom-user-pages-functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_happycula_custom_user_pages() {

	$plugin = new Happycula_Custom_User_Pages();
	$plugin->run();

}
run_happycula_custom_user_pages();

