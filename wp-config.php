<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', 'C:\wamp\www\wordpress\wp-content\plugins\wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'beanbags');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'v7{&U$1rR`?h71+#jI-u$[}[iVcg4(@]cIbZKQ!5[1*`!o]|S=8fzQQ8XL%IArT3');
define('SECURE_AUTH_KEY',  'Q7`Kq=)=h tH%gcs7$O}jez|uo+Pimad7`*yQXkIOMC^Oq~eTB4zP>,$J`yT#9mO');
define('LOGGED_IN_KEY',    '=`P8r`/01hzO4TroEogZq!n8)}KxU-YPP9e*DK^:%z(~=TrUb+j222c,Uv+$T}ko');
define('NONCE_KEY',        'BuB; *kilj3I@9A,8WQN~b/}&:w&eguA2zL pf|=QmsAbzH;^[a*XbqcT[Iz!~A ');
define('AUTH_SALT',        '{^il5.?Jy}Rj`_QW(S%nV4yE>]yt]z[j4mO`|PxuYro2`ts9:qoZH5$rD e%lH@U');
define('SECURE_AUTH_SALT', 'LcWTtP!?DI~${*_iQtQ]s<e8Q4>5|e<QS,uXfUD#@CI5u!A.AKvyLm/(VRgE.O6e');
define('LOGGED_IN_SALT',   'k;0s0M!a{Cnv],_xrV5,5WFcn9:2cs@GG.6f#u1Y}/+#R)`Ve6fEKKo_-zC=?~6e');
define('NONCE_SALT',       'Z{fn8p0F2Uhms,?HOl?<|eTlO#u5*pghkRk!>]].2c!G`){.)U^t2fE6KD [-nSW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'bs_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
