<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_newdessa');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         '0{fgdJ=z!g-}YM=iSe2hRbNs6UF?3KwF=8p+)GrGjm!5|]&/?F]QJi3&[-tuz9g}');
define('SECURE_AUTH_KEY',  'UG^*A:els}@|t0agLJZ&-|P2@w-0kC,1 HBLuY%%AV[ArC0s#q+c1W9,R]o^)?F1');
define('LOGGED_IN_KEY',    'Gt9<T=wxIL)8L6^=Q//?r]!Na1>NUT_,8CmQVRm<PM_ Ps{JuPZ&:EBqeCqR[<aU');
define('NONCE_KEY',        'p~H&ghr-CG>(?sTT  +zrG%65,KAuNVU+ovN_ tHTD{mDZHj&s|3E+&.<%k~t9(Q');
define('AUTH_SALT',        'Ul4GRSQOc[oIy1r(uJS-|TsH&,eni32Q5V|;D@=Cc/C^/5m1`P7074)I7QZ#4+0i');
define('SECURE_AUTH_SALT', '3lP*-dCg++bT>Rc,Aqfv+4C8}/MRiqkyuJ8yCi^Hq(+n-4X<Sr }e3m~Gel2|spn');
define('LOGGED_IN_SALT',   ' AnTYCMAjv}/f[70aG2PpcZ$Io>>)q?m$NNu3)u<W_Y@+^zkUH<`S42,P2d{_#ZK');
define('NONCE_SALT',       ')IPEoG/!}Y0oIV>G3P~h$+q|)~zRszwv-_/0qdbZ<$.?c #yuRa-vp{2rOW %qab');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
