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
    $sql = "SELECT id, username FROM users";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "id: " . $row["id"] . " - Name: " . $row["username"] . "<br>";
        }
    } else {
        echo "0 results";
    }
    mysqli_close($conn);
    if (isset($_GET['login'])) {
        $user_selected = $_POST['name'];
        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT id, username FROM users WHERE Username = '$user_selected'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $_SESSION['loggedin'] = $true;
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
    <form method="post" action="?login=1">
        Name: <input type="text" name="name">
        <input type="submit" name="submit" value="Submit">
    </form>
</body>

</html>