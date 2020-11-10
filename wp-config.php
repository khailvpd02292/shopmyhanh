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
define('DB_NAME', 'wp_code');

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
define('AUTH_KEY',         'A]V|axph@4[Xq9<Ew/X8]n,t<=g/_oz h#h1LFh}N;;e9G+XPPe;0A^:lxU@7@u8');
define('SECURE_AUTH_KEY',  '#J|t~M$ISFO8 ?VfCE&FDaZ`_,`k#Y6`TG6pS3%*R&wY7bZJ{jp|!)#WY45Dc!6Y');
define('LOGGED_IN_KEY',    'XF4AeIMAdZTn]!E|<MNq?H4w0`qpumP$FD}<ghXyRmKb?{,u.]y;;k54=JCgCbm@');
define('NONCE_KEY',        'iVr@`|*rb2&p;6=ifhnT=f1:#!|-a2,c>67Lc+CO= Jyvk0}6Ni/|KhHu7z;Sl(I');
define('AUTH_SALT',        '([oR/SGpP9]GZv{(73{J{n,,k2dI{Q__4|&U`^-D`,`3n@4[eo(s@Dfs;C,~W$0w');
define('SECURE_AUTH_SALT', '9u9$As4lHl>4}Xm>xYZsPJ^51c*B{?r|5.]p90b!Fo.a(ngN>&.$7Ib+tsz>bcB%');
define('LOGGED_IN_SALT',   'k y`+BZ~dUf5O3 %JBF6Rak?RF4GHMLhp$Ed6${r(y?7V-1gdEJT-q2K;ju|pVD ');
define('NONCE_SALT',       'CGvL:3dFw(%kNB.JXBQPm=uK$42h)L;@0r_zZ5_%Bu1):BR5u27K[Q{g;NtR/tmL');

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
