<?php	
	define("MARIADB_HOST", "112.222.157.156");   
	define("MARIADB_PORT", "6432");
	define("MARIADB_USER", "team3");        
	define("MARIADB_PASSWORD", "team3");  
	define("MARIADB_NAME", "team3");   
	define("MARIADB_CHARSET", "utf8mb4");   
	define("MARIADB_DSN", "mysql:host=".MARIADB_HOST.";port=".MARIADB_PORT.";dbname=".MARIADB_NAME.";charset=".MARIADB_CHARSET);
	define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/"); 
	define("FILE_LIB_DB", ROOT."lib/lib_db_khs.php"); 
    define("REQUEST_METHOD", strtoupper($_SERVER["REQUEST_METHOD"]));