<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
/*commenting below out to drop in for three separate settings...
define('DB_NAME', 'ssd');
/** MySQL database username */
//define('DB_USER', 'root');

/** MySQL database password */
//define('DB_PASSWORD', 'root');

/** MySQL hostname */
//define('DB_HOST', 'localhost');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
switch ($_SERVER['SERVER_NAME']) {
  case 'localhost' :
    define('DB_NAME', 'ssd');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_HOST', 'localhost');
  break;
  case 'staging.sightseekersdelight.com' :
    define('DB_NAME', 'stagingdatabasename');
    define('DB_USER', 'stagingdatabaseuser');
    define('DB_PASSWORD', 'stagingpassword');
    define('DB_HOST', 'localhost');
  break;
  case 'www.sightseekersdelight.com' :
    define('DB_NAME', 'livedatabasename');
    define('DB_USER', 'livedatabaseuser');
    define('DB_PASSWORD', 'livepassword');
    define('DB_HOST', 'mysql.myhost.com');
  break;
}

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
define('AUTH_KEY',         't/yVzITn/0X%,#S0$Xypv.qb(IV3w-!/O#t9HZ$G*eN[-kB:B^&cxjm2fN.I+F(D');
define('SECURE_AUTH_KEY',  ';}2>Tf5f[9Po!7E^aH|553,j&vfhoQ}h[F1}#^Sn4IXg-?q4@ZPptBSyMj /N{=[');
define('LOGGED_IN_KEY',    'U|3++XK0?seLVj-1<ee:(/+!fsFSQw])]5-$c%6d80x|k%ZU}2/5?gwQy}X;g|AK');
define('NONCE_KEY',        'Xuz8`noK?$zg4T796@jrw1=jgYfmX~+AkUgaM({[7Yl|+W1I~0+kVqV}Ro~Md<+p');
define('AUTH_SALT',        '-@r9{[-9k3ZFxhTA%.yOMs{&i,3O(Ds5f$#D&I+MyClNU)k<~8ZhH(|;>QetJqOn');
define('SECURE_AUTH_SALT', 'J<d5Q_v<=`]lkoR:5>]L_!ED3(a*Zn5nySLSR(:Is9(9A<M4T+b(<5g2c6w0$Wi-');
define('LOGGED_IN_SALT',   'V|vB.@E#)S(^Xb?M5@3x_Laf%pfq{u^)1GlgDf/[B`aM;>y^S ?4C):Zof2)w.>[');
define('NONCE_SALT',       'riD.gcO{N rd9IH0coH_ka-0y,pf9q5qSW_wC<11(62Q?F+HX]ktwMZ/nqnznl,L');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
