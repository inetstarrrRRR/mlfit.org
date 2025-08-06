<?php session_start();
        $string = file_get_contents("dbconf.ini");
        $json_a = json_decode($string);
        $servername = $json_a->servername;
        $username = $json_a->username;
        $password = $json_a->password;
        $dbname = $json_a->dbname;
        $workout_Date = date("Y-m-d");
        $user_id = $_SESSION['id'];
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $query = "INSERT INTO Workouts (Date, User_ID)
        VALUES (?, ?);";
        if (mysqli_execute_query($conn, $query, params: [$workout_Date, $user_id])) {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $last_id = mysqli_insert_id($conn);
            $_SESSION['selected_workout_id'] = $last_id;
            header(header: 'Location: sets.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
?>
<html>
<head>
    <title>Workout</title>
    <style>
        body {
            background-color: powderblue;
        }
        table,
        th,
        td {
            border: 1px solid black;
        }
        a:link,
        a:visited {
            background-color: buttonface;
            color: black;
            padding: 25px 25px 25px 25px;
            ;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 20px;
            margin: auto;
            display: block;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
</body>
</html>