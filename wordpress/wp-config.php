<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

# Database Configuration
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress'));
define('DB_USER', getenv_docker('WORDPRESS_DB_USER',  'wordpress_user'));
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD',  'secret'));
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST_PORT',  '127.0.0.1:3306'));
define( 'DB_HOST_SLAVE', getenv_docker('WORDPRESS_DB_HOST_PORT',  '127.0.0.1:3306'));
define( 'WORDPRESS_REACT_APP_HOST', getenv_docker('WORDPRESS_REACT_APP_HOST',  "http://localhost:3000"));

define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );


// define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/NewSiteName');

// define( 'DB_HOST', '172.26.96.1:3009'); -- WSL docker host
// define( 'DB_HOST_SLAVE', '172.26.96.1:3009');

// define( 'DB_NAME', 'teamlemoine' );
// define( 'DB_USER', 'xx' );
// define( 'DB_PASSWORD', 'xxx' );
// define( 'DB_HOST',    '127.0.0.1:3306' );
// define( 'DB_HOST_SLAVE', '127.0.0.1:3306' );

// define( 'DB_NAME', 'wordpress' );
// define( 'DB_USER', 'wordpress_user' );
// define( 'DB_PASSWORD', 'secret' );
// define( 'DB_HOST',    '127.0.0.1:3002' );
// define( 'DB_HOST_SLAVE', '127.0.0.1:3002' );

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

// Enable WP_DEBUG mode
define('WP_DEBUG', true);

// Enable Debug logging to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Disable display of errors and warnings
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

// Enable script debug mode
define('SCRIPT_DEBUG', true);


# Security Salts, Keys, Etc
define('AUTH_KEY',         'R2VJ9oK?CZ}{O$CiQf>n=f%gm X{FWLpII7J)QBM/9#9>(lQb_^6iI#j{v++aa[|');
define('SECURE_AUTH_KEY',  '3aulup!i++b_*N?])kezRn+EJ %~MzJkhdj2G9f-nY0[oj~:DM8>]<bP2Wa6$#Re');
define('LOGGED_IN_KEY',    'MPt?_q?//dlx+&>-Qs*BH%9GpG*:4%tE!*iehMm3=Lh-R{^g**ynT6h{-WYAbSdi');
define('NONCE_KEY',        'h{<}|X-uNbWxG[mGde0_+aj[#uxRQ2SDdoQZxgu:1`1+;a1rp)0fTPw2K<cmpLW(');
define('AUTH_SALT',        '*>3Fm-*;x}8JNH>S-/FoUO)?Cr2/~4si-B:?/k{ 0Wwud<c6<^mlUh)}!B#H]9iL');
define('SECURE_AUTH_SALT', '`|Wh r(|kWD`!f`uDw-/sOdEC-Pin{H-;cQgi{Q|$~^9W?OuA+`vG^($n%2?wsh+');
define('LOGGED_IN_SALT',   '|<C@td/tkx`0&6+FStXy!0T}1+YW,Q_*1UOu5L{LAg`v.0h-bJdYDXMRB].j.?cw');
define('NONCE_SALT',       '*nYDWsdxL|ac(xvW#.OPI*)s4@e>Hx|V;zr<5R:,IkV<[t$$T5GSXLk~|<F_4|S^');

define('JWT_AUTH_SECRET_KEY', 'x_*rm7+)4(xw58#1oxo^6qy530&v+%x6_9_+p#g_nq$9wujz!q');
define('JWT_AUTH_CORS_ENABLE', true);

# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'teamlemoine' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'WPE_APIKEY', '352e7a5baf1458ab8e06c576b0fb717072290f10' );

define( 'WPE_CLUSTER_ID', '209108' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_SFTP_ENDPOINT', '35.203.109.224' );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );


$wpe_all_domains=array ( 0 => 'teamlemoine.wpengine.com', 1 => 'teamlemoine.wpenginepowered.com',  2 => 'localhost' );
// $wpe_all_domains=array ( 0 => 'localhost:4001' );

$wpe_varnish_servers=array ( 0 => '127.0.0.1', );

$wpe_special_ips=array ( 0 => '35.203.111.177', 1 => 'pod-209108-utility.pod-209108.svc.cluster.local', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');