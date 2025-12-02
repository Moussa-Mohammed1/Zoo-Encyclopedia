<?php 
// database configuration
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $name = 'zoo';

    $conn = mysqli_connect($host, $user, $password, $name);
    if (!$conn) {
        echo "Could not connect !";
    }
?>