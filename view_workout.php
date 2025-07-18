<?php session_start();
    //echo '<p>Hello World</p>';
    //echo $_SESSION['id'];
    if (isset($_GET['edit_workout'])) {
        foreach ($_POST as $name => $val) {
            $workout_id_to_Edit = $name;
        }
        $_SESSION['selected_workout_id'] = $workout_id_to_Edit;
        header(header: 'Location: sets.php');
    } else {
          $servername = "db5018212067.hosting-data.io";
          $username = "dbu3658664";
          $password = "";
          $dbname = "dbs14428786";
        $user_id = $_SESSION['id'];
        //echo $user_id;
        echo "<h1 style='text-align:center'>Workouts:</h1>";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "Select Workouts.Date, 
    Workouts.ID, 
    Sets.Reps, 
    Sets.Weight, 
    Sets.Volume, 
    Exercises.Name
    From Workouts
    INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID
    INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
    WHERE Workouts.User_ID = '$user_id' ORDER BY Workouts.ID DESC;";
        //echo $sql;
        $result = mysqli_query($conn, $sql);
        //var_dump($result);
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
                //var_dump($workout);
                //$Workoutdatetext = "Workout: " . $workout[0]['Date'] . " (ID" . $workout[0]['ID'] . ")";
                $Workoutdatetext = $workout[0]['Date'] . " (ID" . $workout[0]['ID'] . ")";
                echo "$Workoutdatetext";
                $selected_workout_id = $workout[0]['ID'];
                $Button_for_Edit = "<form method='post' action='?edit_workout=1' style='display: inline;'>   
            <input type='submit' name='$selected_workout_id' value='Edit_Workout'> <br>
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
        mysqli_close($conn);
    }
    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Workouts</title>
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

</body>


</html>
