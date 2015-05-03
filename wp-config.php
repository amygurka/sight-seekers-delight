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
switch ($_SERVER['SERVER_NAME']) {
  case 'localhost' :
    define('DB_NAME', 'ssd');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_HOST', 'localhost');
  break;
  case 'staging.sightseekersdelight.com' :
    define('DB_NAME', '95386gglad');
    define('DB_USER', 'sight95386');
    define('DB_PASSWORD', 'toto123');
    define('DB_HOST', '91.216.107.248');
  break;
  case 'www.sightseekersdelight.com' :
    define('DB_NAME', 'sight95386');
    define('DB_USER', 'sight95386');
    define('DB_PASSWORD', 'toto123');
    define('DB_HOST', 'mysql8.lwspanel.com/myadmin4');
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
define('AUTH_KEY',         'n#?Uv,;DF.d$Mc<wl;2{OR*]A+9R4xY(c|J)O.!N0+kMJ^-epk8ewJLU]&4)0`|b');
define('SECURE_AUTH_KEY',  '>^D45~3H7}?PZ$c`N#2.soK*QcGkR|kRO5,3F|`m*|;pT[]E~c29&_7hBGa58,wK');
define('LOGGED_IN_KEY',    'z36;_Jmj.?``;o|aTyo)0-fzXX09QU]l.Q)?,.[ziVpG^U+?=!Smvw&pkWoI(U+g');
define('NONCE_KEY',        '9*o${:+(d_fH|<Ro=~kcX{`H-.P39S0 KRyn$wz%.8:khACqas;`ItX?a+Fsb!T<');
define('AUTH_SALT',        'OBjf9Mk~Y::xU7H9V+R<0qchao(98Pl(3VcyFF@VV?|Gv!<N9GYc6`-zSHU)_s,G');
define('SECURE_AUTH_SALT', 'gv+j4V+IW-X?^>!(E.9FEW0q;KiS@P:pq6Q/R5hy?ML9oPxSp7JD,&pdQL_B,AK2');
define('LOGGED_IN_SALT',   '~FUsX/;?Bzu)BC(SrBL88!]CJ:2eay+KaaTdAb&56nI]EXuc7]_G_OcSko[}whzy');
define('NONCE_SALT',       'ex?pABq<rWr.HU-dlN?3&or%zSlv*prWL-@)sE_[E|x%ZNQ4A^9rY!>!psE8}nB:');

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
