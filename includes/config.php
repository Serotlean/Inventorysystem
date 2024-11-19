<?php

define('DB_HOST', 'localhost');          // Set database host
define('DB_USER', 'root');               // Set database user
define('DB_PASS', '');                   // Set database password
define('DB_NAME', 'inventory_system');   // Set database name

// Create connection using the defined constants
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection was successful
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>
