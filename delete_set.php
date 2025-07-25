<?php session_start();
    $string = file_get_contents("dbconf.ini");
    $json_a = json_decode($string);
 	$servername = $json_a->servername;
    $username = $json_a->username;
    $password = $json_a->password;
    $dbname = $json_a->dbname;
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['delete'])) {
        $set_to_delete = $_POST['delete'];
        //$sql = "DELETE FROM Sets WHERE ID = '$set_to_delete';";
        $query = "DELETE FROM Sets WHERE ID = ?;";
        if (mysqli_execute_query($conn, $query, [$set_to_delete])) {
            header(header: 'Location: sets.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Set</title>
    <style>
        body {
            background-color: powderblue;
        }
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
</body>
</html>
