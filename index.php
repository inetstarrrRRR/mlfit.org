<?php session_start();
$string = file_get_contents("dbconf.ini");
$json_a = json_decode($string);
$servername = $json_a->servername;
$username = $json_a->username;
$password = $json_a->password;
$dbname = $json_a->dbname;
if (isset($_GET['login'])) {
    $user_selected = $_POST['name'];
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //$sql = "SELECT id, username FROM users WHERE Username = '$user_selected'";
    $query = "SELECT id, username FROM users WHERE Username = ?";
    $result = mysqli_execute_query($conn, $query, [$user_selected]);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['loggedin'] = True;
            $_SESSION['id'] = $row["id"];
            $_SESSION['username'] = $row["username"];
        }
        header(header: 'Location: workout.php');
    } else {
        echo "user not found";
    }
    mysqli_close($conn);
}
?>
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
    <h2>
        <form method="post" action="?login=1">
            Name: <input style="height:60px;font-size:14pt;" type="text" name="name">
            <input style="height:60px;font-size:14pt;" type="submit" name="submit" value="Submit">
        </form>
    </h2>
</body>

</html>