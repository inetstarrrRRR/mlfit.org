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
    <?php
    session_start();
    echo '<p>Hello World</p>';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "workout_site";
    $user_id = $_SESSION['id'];
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
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
    $result = mysqli_query($conn, $sql);
    //var_dump($result);
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
            $Button_for_Edit = "<form method='post' action='?edit_workout=1' style='display: inline;'>   
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

        /*
        foreach ($sets_from_workout as $set) {
            if ($id != $set['ID']) {
                $id = $set['ID'];
                $table = "
                <table style='width:100%'>
                <tr>
                    <th>Name</th>
                    <th>Reps</th>
                    <th>Weight</th>
                    <th>Volume</th>
                </tr>";
                $new_table = True;
            }
            $table = $table . "
                           <tr>
                           ";
            $table = $table . "<td>" . $set['Name'] . "</td>";
            $table = $table . "<td>" . $set['Reps'] . "</td>";
            $table = $table . "<td>" . $set['Weight'] . "</td>";
            $table = $table . "<td>" . $set['Volume'] . "</td>";
            $table = $table . "<td>" . $set['ID'] . "</td>";
            $table = $table . "
                          </tr>";

        }
        $table = $table . "
                      </table>";
        echo $table;*/
    } else {
        echo "0 results";
    }
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    ?>
</body>

</html>