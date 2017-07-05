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
define('DB_NAME', 'fxprotoo_fx9R0t00ls');

/** MySQL database username */
define('DB_USER', 'fxprotoo_fx9m1n');

/** MySQL database password */
define('DB_PASSWORD', 'Classic356869!*');

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
define('AUTH_KEY',         '(6ZQfn%R!H=6,fYXglibbL1RL2$>OH;?BN/$8Zu?}r;7rkxc>*QCPvB8s#Esuljb');
define('SECURE_AUTH_KEY',  '`p}d1pKRH!d{w?S>4Zd`NbeVO-RA.]p0XQZhRrn<1S-[lwrc.BsiEMf~kx e||O~');
define('LOGGED_IN_KEY',    '`?mn^}<hKm$k`/{{eV{5t3)S5b_rSskEqYI(h@Q8@E/6DzW4lf:1cM5nn/`p_-hZ');
define('NONCE_KEY',        'TVK]yE}R;:D}UM^GGgwbIT<]Omnlv:!8z0sX;b[bt@UyxTt8OFqJS?Ez@|04.5b]');
define('AUTH_SALT',        ';n}[y()!O}Dj/[JVU!pM2dw:o+oHiu&D1,xw4C(;=$?ycyTO([wtQ7`~b)c:4e_!');
define('SECURE_AUTH_SALT', '/[NR((+Jco 4Z <G6~Y]vYOUdWm|{Tluy^Z G9K_i>FTQ0k@L9B#2DS%Z&/~E8O4');
define('LOGGED_IN_SALT',   '60iO>h8GR+%0a)LY6cW19-hU;HhAq7G.d_Xj`RR;@T7EmjT7$&.0;!#jU41.;6EP');
define('NONCE_SALT',       '%V$u!f1zdiV*ORSwc:RI.17cb;Hqq4x@STl~5tWLgJXu4r:_c[~c=f3):R~w`in^');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
