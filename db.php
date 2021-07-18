<?php
$db_user = "root";
$db_pass = "";
$db_name = "charity";

try {
    $db = new PDO('mysql:host=localhost; dbname=' . $db_name . ';charset=utf8', $db_user, $db_pass);
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}

?>