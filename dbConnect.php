<?php
$db = "nyhetssida";
$user = "root";
$password = "";
$server = "localhost";

$connection = new mysqli($server, $user, $password, $db);
if ($connection->connect_error) {
    die("Could not connect to db");
}
$connection->set_charset("utf8mb4");