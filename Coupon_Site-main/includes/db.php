

<?php
$host = "localhost";
$username = "root"; // Change this for live server
$password = "";     // Add password if needed
$database = "coupon_site";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
