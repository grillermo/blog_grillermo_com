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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'e48fa49fc228ea6804fe0b158c15b6a0ed31b1f0655134c5' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         '`6gyo)_oI92n(d<f!1wKb!42grcX(`cl]7T:`4=3^O$y.2B`7d_:~id}74e>wbv~' );
define( 'SECURE_AUTH_KEY',  'UF8d[:E[A3qwohi(G8+/{)UGA>i}zX^PN3:EY]upnt)$LR],XNq4q~3R y]UIA-.' );
define( 'LOGGED_IN_KEY',    '`bFwf2a!wl0(U& Bwi0`L.Uxx0rh/*9N/ Ve+JE~oauFSiRs(jBB)3V^MHRDNCUD' );
define( 'NONCE_KEY',        '!xS_;9bBVwn,XPJs7XRjEEdwYPN+;>*E@W^*C5FEJmA#g#.5-8K%l]|dFu.f?f(@' );
define( 'AUTH_SALT',        '{I[M-j<[%t*x(lds,%UIT/.QB(jqeRvz9sAXpPvX(;qHLf:nAh6s/1c+MAQBOV)M' );
define( 'SECURE_AUTH_SALT', '@.e$n )hRT}F3%F(GKd]pM+AEta J|e&A%yGDaF]H/6l6@+:lyRogC,JpY?<Yfac' );
define( 'LOGGED_IN_SALT',   '.8Xg(}F+#C0V>6{Idnj6iHemgkk;/vONx(l}$G0Rti$JZD*XV*i`tTS1!AZ%W&<@' );
define( 'NONCE_SALT',       'Dq`g`EG4K#vsz??!>ui[#}3GBr)q&ke78<czjuC#r#+xxpymv#9jbY?oOA5Pc YR' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
