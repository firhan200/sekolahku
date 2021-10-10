<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'db_sekolahku' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Z:9[7b9WE_$aU[d+Zf!K76xh Uz<k&,]oUU5#KDC=a<Z;JmdAuqK=C!aLzMh@O?+' );
define( 'SECURE_AUTH_KEY',  'ZE/|-@<eH3chO?,W#|90tnc5lh6: 1[HC;Gs)JdM~(>R!UTU+Q$adD^:_Xb^QFvj' );
define( 'LOGGED_IN_KEY',    'v/HP6,jElZh|~qDmYwe6=OC]6DfI/yipS2O$Jw/O,3>/Vbegj[}V-85PLsT&qH*5' );
define( 'NONCE_KEY',        '#xG&]BB^EJ`B3zr8yJib,$3c>pb~B@7{&^X?s[5!HfOndzkH-0%k1icrT-9gcw/M' );
define( 'AUTH_SALT',        'Odd?N#5dc<=FsRM0|NW^y+ r,wEfk7:ygMW Q:TnU;+Wv[9C3@v&e&m^;6)ud{Du' );
define( 'SECURE_AUTH_SALT', '-AitNV3/Z*p1?tn5M7C40;,CYAYQxpkfR/%:F%s8VmAy$@W[`E4R|@Ij1)qP{9i;' );
define( 'LOGGED_IN_SALT',   '~1b].@A.brMqJQapq#|/+y;X(>IT!2x48vc}_y9EqI.X(`$pPY.cR.IU9heWq[ji' );
define( 'NONCE_SALT',       'WTh&3%-~jb.%aoq9z(:;UX!T/;0G 71]eaWuU#VeoxU4o(cASo*[MHLy_R?Dg5bl' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
