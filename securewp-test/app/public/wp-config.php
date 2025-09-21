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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'p=hZN{56_IMsc?C4)Wfr IhTi ?aqbhL%s;<v( WAZ)PskmkTxjg}[uz7zu(egMA' );
define( 'SECURE_AUTH_KEY',   'E;k)6Xh;>*}J29Xwy&*]0hANK^y]@#^ZA{x?<;g&k}yY/|bs/iqZcxVfrOgxG1zE' );
define( 'LOGGED_IN_KEY',     'XTneJoG<oWj=5(.G}Ys3u75jD Sv;Pd6FP53I?|YnU]U8 Map.ocGY:7:IdK.p~r' );
define( 'NONCE_KEY',         'sw*0~mW$-k(|{B*?[}VE7^yKJ$Z>16k_}+j@BLK03.5MHE5[z!^>(NG?*$.m^Yhm' );
define( 'AUTH_SALT',         'YV@d4)c%|g%@eeNrQhVO(:ud:^=z#m[NsZ-4jaQE+@hDFF%Hw6DXpuIY&W<]X3du' );
define( 'SECURE_AUTH_SALT',  'z=+lRd@oMi{92a(aW%$5;G2.9{$au`u)6@,GN2>D&jAI6759_W>T}:Q<w/&3GV}m' );
define( 'LOGGED_IN_SALT',    ' [s}B_/u_aXkqiPPu@$v*uUOlJq:s HZP&`:|1;GU`T7Hf(5.m^,=<H)hneUPe3w' );
define( 'NONCE_SALT',        'q-$.r !CY[&UaeFbw:-jhxv~)y=ct_(PhS=2O#||J6:tP0~}N5ldg]Y)Jn/TkDi`' );
define( 'WP_CACHE_KEY_SALT', 'Px%5nAd2|wfU;8 +$fyXgiZI%MhJQQeD$22$om)-^/)pgGI# xQ4>%=0j0.Wef{^' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
