<?php
define( 'WP_CACHE', true ); // Added by WP Rocket
define( 'WP_CACHE', true ); // Added by WP Rocket

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'khyawzwky8ypdmca' );

/** MySQL database username */
define( 'DB_USER', 'kHyawzWKY8ypdMcA' );

/** MySQL database password */
define( 'DB_PASSWORD', 'h34w3kDdr3tYzGNa' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'TX9Rh(qx`,~81/NJA2]&,cBJc+8uYk/q^qGp kM>D^4ncl,Fk=c/ S(Jb2uq+-D0' );
define( 'SECURE_AUTH_KEY',  'h`)0.FS^oeHbju_BL|gwg-qXmhwb8!~ON$`b#+J~XH`HHj%szMST|=g$1]dM:Kek' );
define( 'LOGGED_IN_KEY',    '&{UsC!Yi6(/NJb[=T/#,/{8rh,G|drs!gNCSp,xaog#Ijh4~~~i_#yR@AP/ZeR9#' );
define( 'NONCE_KEY',        'ia<M::Ks.l9D0i#o/G7o8LlL<Rzd>h,`0^pipBe<Uq1-Q&C7-][Lc_srxqiJ{fqL' );
define( 'AUTH_SALT',        '2Ttni<{%3pU)d`~:YvQJf},/3~AMmLjJ11[~4r_,ph[M>[|;kZKEV`!oQjtO8c]&' );
define( 'SECURE_AUTH_SALT', '5zSC`]#`W; (`!T)#_q^gu1:F^$=.&6l>gMj>1`qs$z^8kE{b:7ax1V7B:u/M 3w' );
define( 'LOGGED_IN_SALT',   '>-T`!9</?{JedGNb@[2xY(Cln$2p*!ui(V^7[}=C`)eg.vt~f*$R*c,Z3Z3liQ97' );
define( 'NONCE_SALT',       ';.Z2;C1,M3FI%)?D%a_fT2jK}D`+<HHL3SfX/.&SM46q|q?;]3DAtg1[p1Kx{c!~' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'hwkjq_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

/**开启memcached+Batcache*/

define('ENABLE_CACHE', true);
define( 'WP_CACHE', true ); // Added by WP Rocket
