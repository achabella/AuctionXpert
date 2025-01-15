<?php


// host
if (!defined('HOSTNAME')) define("HOSTNAME", "localhost");

// DB name
if (!defined('DBNAME')) define("DBNAME", "homeland");

// user
if (!defined('USER')) define("USER", "root");

// password
if (!defined('PASS')) define("PASS", "");

try {
    // create a new PDO instance
    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME, USER, PASS);

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "DB connected";
} catch (PDOException $e) {
    // handle connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>
