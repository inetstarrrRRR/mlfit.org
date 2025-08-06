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
foreach ($_POST as $name => $fav_to_delete) {
    $fav_to_delete = $name;
}
$query = "DELETE FROM Fav_Ex WHERE ID = ?;";
if (mysqli_execute_query($conn, $query, [$fav_to_delete])) {
    header(header: 'Location: add_fav_ex.php');
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Delete Fav</title>
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