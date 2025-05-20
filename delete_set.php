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
    <?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "workout_site";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['delete'])) {
        $set_to_delete = $_POST['delete'];
        echo $set_to_delete;
        echo $_SESSION['selected_workout_id'];
        $sql = "DELETE FROM Sets WHERE ID = '$set_to_delete';";
        echo $sql;

        if (mysqli_query($conn, $sql)) {
            echo "SET Deleted";
            sleep(1);
            header(header: 'Location: sets.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
    ?>
</body>

</html>