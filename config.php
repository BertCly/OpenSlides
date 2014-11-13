<?php
/**
 * These are the database login details
 */
if($_SERVER["HTTP_HOST"] == 'localhost:8080' ){
    define('ADMIN_HOME','http://localhost:8080/masterthesis/admin');
    define('FRONTEND_HOME','http://localhost:8080/masterthesis/frontend');
    define('ASSETS_HOME','http://localhost:8080/masterthesis/assets');
}
else{
    define('ADMIN_HOME','masterthesis.azurewebsites.net/admin');
    define('FRONTEND_HOME','masterthesis.azurewebsites.net/frontend');
    define('ASSETS_HOME','masterthesis.azurewebsites.net/assets');
}

define("HOST", "dciilsfcal.database.windows.net");     // The host you want to connect to.
define("USER", "IndepenWPFApp@dciilsfcal");    // The database username.
define("PASSWORD", "5v8Gxwzj");    // The database password.
define("DATABASE", "MasterThesis");    // The database name.
define("DATABASETYPE", "mssql"); //choose between mssql and mysql

define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

define("SECURE", FALSE);    // FOR DEVELOPMENT ONLY!!!!

/** Absolute path to the home directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');