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
define('DB_NAME', 'portfolio');

/** MySQL database username */
define('DB_USER', 'daragh');

/** MySQL database password */
define('DB_PASSWORD', '6ee3270c73');

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
define('AUTH_KEY',         'Re|imSj&IbGH:&,>y+9niFMFG2_X$91:Mn1C;|3dy,qdCbjfaJjV^ok>*p*0w}5%');
define('SECURE_AUTH_KEY',  'e/X~a-dD$d4j<b$*1vyo[ !i#Kng~ZnjjtL{evWk!5eO)@W&,-dYD u^Z.^bbtRb');
define('LOGGED_IN_KEY',    '2b[|A`,/rm5@>#d`gTasqSD$>:5Q&@3N|Ym@W=h#x<Q}jV60bm16)~~dQgbs=@OR');
define('NONCE_KEY',        'DcQ+;Xuqc}Jb5~_,IG`r,8V{jSCZyq76xP=siTE&?.T%bF(~B5]%I8-<xv4a$R~@');
define('AUTH_SALT',        ']5Ci4;f]DaGRR4y|wOI.Xb)5F-tSt{lRgJc9u73`s6[/bn?rS.XCB<O:jH$Zc},)');
define('SECURE_AUTH_SALT', '*d-Z!cPAL$8%&mwiRC60<bg G|v6lg{WeEpGJ++1v?4U3&[q7:aZtxeIb6%NPC?(');
define('LOGGED_IN_SALT',   'HnEnI7LkUc,Lbeb]iF:4Nl.`IQ06?+!_qmV;Pi#Xou S<J8rl:{:jbSRUvbnGwlN');
define('NONCE_SALT',       '#D4+<`*j@4{fJZrSKCxFq&}0,|_k=/iX&@E~:bq2yL#~.9DoT%oVQRh:QblfS>+3');

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