<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'louklouk');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'tresor');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Z4|Dum&HO$6BS[7]k6h{YD4Ty+4`7Bs0aIk];la]OzS#y~e6]|C!Q7ogG*N|%+W.');
define('SECURE_AUTH_KEY',  'L18}G/#x1gWVXGsX7Z{};Gy%OFNQ{Dg#=Up9lwiW<fYQ-hwb3Tczn&IUE1TH+ZKL');
define('LOGGED_IN_KEY',    'aF<+w<$_ ]IR>q>zNaP?zQ}/g%N?hPV+Ld$d)Iq<i=lJ5k!tQ}Pp.++h@--aT^eP');
define('NONCE_KEY',        'PBdYG%`4D)W:U%~vB|D%cp@E ZOA)6CqyJ8(10s2b|mF|W>l||p+HFnxfs?OF14s');
define('AUTH_SALT',        'Y~2}]EWoL%#wJ?3nbg;v?wI=,1N(^.rDB(p}hH_f-qB_IJsl.mR1px|sqw)xOs}b');
define('SECURE_AUTH_SALT', '+rj9gie;zUJ]T3)Pe8&drrvh@b4?{{DbQRK [+F$9#sfCpJWT%TRa8X+tyY+N+;S');
define('LOGGED_IN_SALT',   '{IE1#3eNNwoh#I6V5@Y_@nbB57,Ym(k{Do:6I=GD[b8eJPnV;Huoy:L[;-&#D4gk');
define('NONCE_SALT',       '^2BQ7ka.fL0:GzHb]$n>,yk4`C#o<U-i1<7Yyz|i{J<_$vx3R=UlyZH&lt5me#Tr');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
*Trésor Tshishi
*
*Allow multisite
*
*/
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'www.louklouk.local');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
