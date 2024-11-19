<?php

define('DBHOST', '192.168.50.114');          // Set database host
define('DBUSER', 'root');               // Set database user
define('DBPASS', '');                   // Set database password
define('DBNAME', 'fishop');   // Set database name

// Create connection using the defined constants
$con2 = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

// Check if the connection was successful
if ($con2->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
