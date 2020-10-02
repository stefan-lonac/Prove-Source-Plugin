<?php 

    // Connect to the Database of the current website
    require_once(ABSPATH . 'wp-config.php');
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_select_db($connection, DB_NAME);
