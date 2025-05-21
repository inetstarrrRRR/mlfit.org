<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <style>
        body {
            background-color: powderblue;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>

<body>
    <?php
    session_start();
    echo '<p>Hello World</p>';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "workout_site";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    ?>
</body>

</html>