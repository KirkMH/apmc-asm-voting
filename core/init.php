<?php
// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dbzzq47j9ry2a7' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

# ========== PRODUCTION =============

// define( 'DB_USER', 'root' );
// define( 'DB_PASSWORD', '1234567890-' );
// define( 'DB_HOST', 'localhost' );


# ============ ACTUAL ===============

define( 'DB_USER', 'u30kqmttvpqvl' );
define( 'DB_PASSWORD', '3}2i@$lpks_y' );
define( 'DB_HOST', 'localhost:3306' );


require_once 'MyDb.php';
require_once 'Input.php';
require_once 'Redirect.php';
require_once 'F1ITSS.php';
require_once 'Validate.php';

session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>