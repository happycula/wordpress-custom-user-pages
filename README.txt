=== Happycula Custom User Pages ===
Contributors: happycula
Tags: user, user login, login, custom login, custom user login, registration, custom registration, custom user profile, user profile, user registration, custom user registration
Requires at least: 5.0
Tested up to: 5.1
Stable tag: trunk
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom user pages: login, registration, profile…

== Description ==

Custom user pages for Wordpress:
- login
- registration
- lost password
- reset password
- edit profile

Custom emails:
- lost password
- password changed

== Installation ==

1. Upload `happycula-custom-user-pages` to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create the required pages if they don't already exist
4. Use the Settings->Custom User Pages screen to configure the plugin
5. You can override default templates by creating a directory `happycula-custom-user-pages` in your theme. Templates available:
- `account.php`
- `edit-profile.php`
- `logged-in.php`
- `login-form.php`
- `lostpassword-email.php`
- `lostpassword-form.php`
- `password-changed-email.php`
- `register-form.php`
- `resetpassword-form.php`

== Screenshots ==

1. Settings page

== Changelog ==

= 1.0.3 =
* Fix french translation.

= 1.0.2 =
* Redirect after login now works as expected.

= 1.0.1 =
* Bugfix on registration form: display error when firstname or lastname missing.

= 1.0.0 =
* First release of the plugin.