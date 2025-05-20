<!DOCTYPE html>
<html>

<head>
    <title>Workout</title>
</head>
<style>
    body {
        background-color: powderblue;
    }
</style>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />


<body>
    <?php
    session_start();
    //echo '<p>Hello World</p>';
    //echo $_SESSION['id'];
    $date = date('Y-m-d');
    echo "
    Add Workout:
    <form method='post' action='?add_workout=1'>
        <input type='date' name='datepicker' id='datepicker' value='$date' />
        <input type='submit' name='Add_Workout' value='Submit'>  <br>
        <input type='submit' name='View_Workout' value='View/Edit Workouts'> <br>
        <input type='submit' name='delete_Workout' value='Delete Workout'>
    </form>"
    ;
    if (isset($_GET['add_workout'])) {
        if ($_POST["Add_Workout"]) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "workout_site";
            $workout_Date = $_POST['datepicker'];
            //echo $workout_Date;
            //echo nl2br("\n");
            $user_id = $_SESSION['id'];
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "INSERT INTO Workouts (Date, User_ID)
        VALUES ('$workout_Date', $user_id);";
            //echo $sql;
            echo nl2br("\n");
            if (mysqli_query($conn, $sql)) {
                //echo "New record created successfully";
                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                $sql = "select ID FROM Workouts ORDER BY ID DESC LIMIT 1;";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected_workout_id = $row['ID'];
                        $_SESSION['selected_workout_id'] = $selected_workout_id;
                    }
                    header(header: 'Location: sets.php');
                } else {
                    echo "0 results";
                }
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
    }
    ?>
</body>


</html>