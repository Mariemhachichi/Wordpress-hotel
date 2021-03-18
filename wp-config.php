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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hotel4' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'Dm#*YLltO.uSv(EXVKn#)VY|yp@-8fj5JoFEx!;V3vtl>R@&CSZDRN^@8?l7k>];' );
define( 'SECURE_AUTH_KEY',  '3mu6aK.%G=o-]E<J[@E8PKorh %)#P2D@/H#A,{,O4(#Rl-,30mqW{r.Y -oBI*X' );
define( 'LOGGED_IN_KEY',    'Uayw9K,[[/}pvRY9G$X(m(f/3htyr~tdK3=N.jb[3Y?GIA~?hV}(slb0Qk_*{_XA' );
define( 'NONCE_KEY',        'R*Ku8]|TmotkOi #g:);m)S{.|^=W#`;>sZuQypm&B7Afr>x5m-]0mXxG*%-N@,|' );
define( 'AUTH_SALT',        'V1+=QZ3c-@GZSlL>(eVHONwEZM6D^!)UCcxM*=2SIkF#o1$MH7)mR];OtCP<us< ' );
define( 'SECURE_AUTH_SALT', 'MP%-m:D68Q%73KNF0?=r{FCB^Ze.?&Qw,~nWO1h25|T-`&Erfw8}?^:*e&C(b0Xp' );
define( 'LOGGED_IN_SALT',   '7/n`$VkU-Y[y%P1&nD;?[3uyKtz4k*u=l,$-hlx&%4)Wl,CH5%!m0FB%x<NR0[y;' );
define( 'NONCE_SALT',       '6O#jgv)>gckr[sO}{P,5+;Xi,]$(NIIZg=O][e=sB x_9W5oQVY;ytc-{hi^s6.M' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define( 'FS_METHOD', 'direct' );
