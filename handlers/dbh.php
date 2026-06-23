<?php

    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database_name = "joseph_mosanya";

    $conn = mysqli_connect($server_name, $username, $password, $database_name);
    if (!$conn) {
        die('Database connection failed: ' . mysqli_connect_error());
    }
    mysqli_set_charset($conn, 'utf8mb4');