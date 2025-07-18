<?php session_start();
 	$servername = "db5018212067.hosting-data.io";
    $username = "dbu3658664";
    $password = "";
    $dbname = "dbs14428786";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['delete'])) {
        $set_to_delete = $_POST['delete'];
        $sql = "DELETE FROM Sets WHERE ID = '$set_to_delete';";
        if (mysqli_query($conn, $sql)) {
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
