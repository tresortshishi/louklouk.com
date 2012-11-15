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



switch (gethostname()) {
	case "ubuntu":
		define('DB_USER', 'root');

		/** MySQL database password */
		define('DB_PASSWORD', 'tresor');
		break;
	
	default:
		
		/** MySQL database username */
		define('DB_USER', 'root');

		/** MySQL database password */
		define('DB_PASSWORD', 'root');
		break;
}


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
define('AUTH_KEY',         'U./lux|{i2{:XD}bLb)S4|D6]Ub/H5Gf,{$ghCBypyFuRnNdKp;cA{(Cf]AH8itZ');
define('SECURE_AUTH_KEY',  'sH3rxeN`q4q~W#=`<O|i8,sX~^Uok*fqCN:`zI]Wa8H;57Y!/2dZ<%zWA9hLe{6-');
define('LOGGED_IN_KEY',    'lgcD#X/js,hN TfPM[DsvS<YB^Hb2O_x)bAk}|fT^9S%enO]I X(AF{1c{?~=^h{');
define('NONCE_KEY',        '`><Q.X~hr 0(KXhWwe&Ro(qn/DK0$UA__`WA2~U2(n$`)W<KuMJ8< evP^d40n|_');
define('AUTH_SALT',        'F5p?f=c@+,WM.FHaCz9Wv6r;eV`>Me[IvagA>y~NL.?O-wj+ljGEI}q;6EcoKlwr');
define('SECURE_AUTH_SALT', '`>;(II%2/{uzm0~iS8ifCC{F>?aidVaX|;!RVqlwz~u@yi6PoZ~U{TgaA^@P)65=');
define('LOGGED_IN_SALT',   'RF?{2q-,?x0K#4cg`-#esg~lyDq4#Vy88Fe]m,A2Y3i%?y|X7]*t6.KR2-FVF]S:');
define('NONCE_SALT',       '>FSGS-#t3X|>a!+bYRGt0]u2N;n !:-yogd1X0 <r}+[; m0Bi(5wCIIJM}5wtQ-');

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
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_ALLOW_MULTISITE', true);
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
$base = '/louklouk/';
define( 'DOMAIN_CURRENT_SITE', '127.0.0.1' );
define( 'PATH_CURRENT_SITE', '/louklouk/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
