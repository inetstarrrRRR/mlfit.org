<?php session_start();
//echo '<p>Hello World</p>';
$string = file_get_contents("dbconf.ini");
$json_a = json_decode($string);
$servername = $json_a->servername;
$username = $json_a->username;
$password = $json_a->password;
$dbname = $json_a->dbname;
$user_id = $_SESSION['id'];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
//Delete Workouts
if (isset($_GET['delete_workout'])) {
    foreach ($_POST as $name => $val) {
        $selected_workout_id = $name;
    }
    //echo $selected_workout_id;
    //Write Set to DB
    //$sql = "DELETE FROM Workouts WHERE ID = $selected_workout_id";
    $query = "DELETE FROM Workouts WHERE ID = ?";
    //echo $sql;
    //echo $sql;
    if (mysqli_execute_query($conn, $query, [$selected_workout_id])) {
        unset($_POST);
        //header('delete_workout.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    //Ende Write Set to DB     */
}
//Show Workouts
/*
$sql = "Select Workouts.Date, 
Workouts.ID, 
Sets.Reps, 
Sets.Weight, 
Sets.Volume, 
Exercises.Name
From Workouts
LEFT JOIN Sets ON Workouts.ID = Sets.Workout_ID
LEFT JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
WHERE Workouts.User_ID = '$user_id' ORDER BY Workouts.ID DESC;";
*/
$query = "Select Workouts.Date, 
    Workouts.ID, 
    Sets.Reps, 
    Sets.Weight, 
    Sets.Volume, 
    Exercises.Name
    From Workouts
    LEFT JOIN Sets ON Workouts.ID = Sets.Workout_ID
    LEFT JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
    WHERE Workouts.User_ID = ? ORDER BY Workouts.ID DESC;";
$result = mysqli_execute_query($conn, $query, [$user_id]);
//var_dump($result);
$id = 0;
$sets_from_workout = [];
$i = 0;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($id != $row['ID']) {
            $id = $row['ID'];
            if ($sets_from_workout != NULL) {
                $workouts[] = $sets_from_workout;
                $sets_from_workout = [];
            }
        }
        $i++;
        $sets_from_workout[] = $row;
        if ($result->num_rows == $i) {
            $workouts[] = $sets_from_workout;
        }
    }
    //var_dump($workouts);
    foreach ($workouts as $workout) {
        //$Workoutdatetext = "Workout: " . $workout[0]['Date'] . " (ID" . $workout[0]['ID'] . ")";
        $Workoutdatetext = $workout[0]['Date'] . " (ID" . $workout[0]['ID'] . ")";
        echo "$Workoutdatetext";
        $selected_workout_id = $workout[0]['ID'];
        $Button_for_Edit = "<form method='post' action='?delete_workout=1' style='display: inline;'>   
        <input type='submit' name='$selected_workout_id' value='Delete_Workout'> <br>
    </form>";
        echo "$Button_for_Edit";
        $table = "
        <table style='width:100%'>
        <tr>
            <th style='width:70%'>Name</th>
            <th style='width:10%'>Reps</th>
            <th style='width:10%'>Weight</th>
            <th style='width:10%'>Volume</th>
        </tr>";
        foreach ($workout as $set) {
            $table = $table . "
                           <tr>
                           ";
            $table = $table . "<td>" . $set['Name'] . "</td>";
            $table = $table . "<td>" . $set['Reps'] . "</td>";
            $table = $table . "<td>" . $set['Weight'] . "</td>";
            $table = $table . "<td>" . $set['Volume'] . "</td>";
            $table = $table . "
                          </tr>";

        }
        $table = $table . "
                      </table>";
        echo $table;
        echo "<br>";
    }
} else {
    echo "0 results";
}
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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

        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <a href="workouts.php">Back</a> <br>
</body>

</html>