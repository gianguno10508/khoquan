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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'quanlikho' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         'qr  obYbHAr5KNNcEDexeY9H(/G?U`GJ$y{xVQe)EMR,6* <[fcdzSp Y%^d,vLj' );
define( 'SECURE_AUTH_KEY',  'K(wal:n,kYdowUaOdWIb4}/noI*g@~y&ppgC19:P|8yH(1hk>}k8HjU/*dpqI[]r' );
define( 'LOGGED_IN_KEY',    'p%/~+XMY!U+/A{p*U5)%l+?b$@ywhBoGU1hqK.+KE2JhaA~.KZ99x~R[cJ:@=Wbo' );
define( 'NONCE_KEY',        '^-Vr_a&wIi42k8LcF5-td]>HqAs1h&-(elmSILt4fC?^>miC+CI[Wjp]}+t^`k+h' );
define( 'AUTH_SALT',        '[F](g%Kz{(yWLdjIr+E*7Eqt4.y6VI0)c1S l2#rQ?:sa:xd%wfAemZWLBqMoB*}' );
define( 'SECURE_AUTH_SALT', 'Ln5F4P&Zb.u/| IAs-NBDG%u|G0M<YcFhfte,W@#BaP]dS28+f=dYyZeh;qsMW5H' );
define( 'LOGGED_IN_SALT',   'ALc1CE0gD*T(^GxEP.6?JN,gBJlDbZ2[Uu}hDr]|s2vJPDs-2?{#$&s3@dfK0sBT' );
define( 'NONCE_SALT',       'HBm8UUu3M!o5>_htD(w(K)5|@f)-U+lE$+1T,CMN8afs69fZ.zUu!_y(~{j` 3E>' );

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
