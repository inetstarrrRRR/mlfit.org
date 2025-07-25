<?php session_start();
if (isset($_GET['add_workout'])) {
    if ($_POST["Add_Workout"]) {
        $string = file_get_contents("dbconf.ini");
        $json_a = json_decode($string);
        $servername = $json_a->servername;
        $username = $json_a->username;
        $password = $json_a->password;
        $dbname = $json_a->dbname;
        $workout_Date = $_POST['datepicker'];
        //echo $workout_Date;
        //echo nl2br("\n");
        $user_id = $_SESSION['id'];
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        //$sql = "INSERT INTO Workouts (Date, User_ID)
        //VALUES ('$workout_Date', $user_id);";
        $query = "INSERT INTO Workouts (Date, User_ID)
        VALUES (?, ?);";
        //echo $sql;
        //echo nl2br("\n");
        if (mysqli_execute_query($conn, $query, params: [$workout_Date, $user_id])) {
            //echo "New record created successfully";
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $last_id = mysqli_insert_id($conn);
            $_SESSION['selected_workout_id'] = $last_id;
            header(header: 'Location: sets.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        //echo '<pre>';
        //print_r($muscle_groups);
        //echo '</pre>';
        mysqli_close($conn);
    }
    if ($_POST["View_Workout"]) {
        header(header: 'Location: view_workout.php');
    }
    if ($_POST["delete_Workout"]) {
        header(header: 'Location: delete_workout.php');
    }
}
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
    <form method='post' action='?add_workout=1'>
        <table style='width:100%'>
            <tr>
                <td>
                    <h1 style='text-align:center'>Add Workout: <br>
                    <a href="add_workout_today.php">Today</a> <br>
                    or Pick a Date
                    </h1>
                    <input type='date' name='datepicker' id='datepicker' style='font-size:20px;
           margin: auto;
        display: block;' />
                    <input type='submit' name='Add_Workout' value='Go' style='font-size:20px;
        padding: 25px 25px 25px 25px;
        margin: auto;
        display: block;'> <br>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="view_workouts.php">View/Edit Workouts</a> <br>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="delete_Workout.php">Delete Workouts</a> <br>
                </td>
            <tr>

        </table>
    </form>
</body>
</html>
