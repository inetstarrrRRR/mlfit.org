<?php session_start();
	$servername = "db5018212067.hosting-data.io";
    $username = "dbu3658664";
    $password = "";
    $dbname = "dbs14428786";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sets</title>
    <style>
        body {
            background-color: powderblue;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }
      details>summary {
 
  list-style: none;
}
summary::-webkit-details-marker {
  display: none
}

summary::after {
  content: ' ►';
}
details[open] summary:after {
  content: " ▼";
}
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>
<body>
    <?php
    $user_id = $_SESSION['id'];
    $selected_workout_id = $_SESSION['selected_workout_id'];
    $sql = "SELECT Date FROM Workouts WHERE ID = '$selected_workout_id';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $selected_workout_date = $row['Date'];
        }
    } else {
        echo "user not found";
    }
    echo "Workout $selected_workout_date (ID:$selected_workout_id)";
    if (isset($_GET['muscle_g'])) {
        //Write Set to DB
        $x_seleted = $_POST['ex'];
        $weight = $_POST['weight'];
        $reps = $_POST['reps'];
        $workout_id = $_SESSION['selected_workout_id'];
        $volume = $reps * $weight;
        $sql = "INSERT INTO Sets (Reps, Weight, Volume, Workout_ID, Exercise_ID)
    VALUES ('$reps', $weight, $volume, $workout_id, $x_seleted);";
        //echo $sql;
        if (mysqli_query($conn, $sql)) {
            unset($_POST);
            //header('sets.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        //Ende Write Set to DB          
    }

    //Draw Sets from Workout
    $selected_workout_id = $_SESSION['selected_workout_id'];
    $sql = "select Sets.ID, Reps, Weight,Volume,Workout_ID, Primary_Muscle, Name
               From Sets 
               INNER JOIN Exercises 
               ON Sets.Exercise_ID = Exercises.ID 
               WHERE Workout_ID = '$selected_workout_id';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sets_from_workout[] = $row;
        }
        if ($sets_from_workout != NULL) {
            $table = "
                   <table style='width:100%'>
                   <tr>
                       <th>Name</th>
                       <th>R.</th>
                       <th>W.</th>
                       <th>V.</th>
                       <th>Delete</th>
                   </tr>";
            foreach ($sets_from_workout as $set) {
                $table = $table . "
                            <tr>
                            ";
                $table = $table . "<td>" . $set['Name'] . "</td>";
                $table = $table . "<td>" . $set['Reps'] . "</td>";
                $table = $table . "<td>" . $set['Weight'] . "</td>";
                $table = $table . "<td>" . $set['Volume'] . "</td>";
                $table = $table . "<td>" . "<form action='delete_set.php' method='post'>
             <input type='submit' name='delete' value='" . $set['ID'] . "'/>
         </form>" . "</td>";
                $table = $table . "
                           </tr>";
            }
            $table = $table . "
                       </table>";
            echo $table;
        } else {
            echo "$sql";
        }
    }
    //Get Muscle Group for Tables
    $sql = "SELECT DISTINCT Primary_Muscle FROM Exercises;";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            //var_dump($row);
            $prim_muscles[] = $row['Primary_Muscle'];
        }
    } else {
        echo "user not found";
    }

    //Draw Tables and Dropdowns
    foreach ($prim_muscles as $muscle_group) {
        //Get Exercieses per Muscle Group
        $sql = "SELECT ID, Name FROM Exercises WHERE Primary_Muscle = '$muscle_group';";
        $result = null;
        $ex = null;
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ex[] = $row;
            }
        } else {
            $ex[] = "error";
        }
        //Add Exercieses to Dropdown
        $html_ex_select = null;
        foreach ($ex as $x) {
            $name = $x['Name'];
            $id = $x['ID'];
            $html_ex_select = $html_ex_select . "<option value='$id'>$name</option>";
        }
        //Echo Dropdown for Muscle Group
        echo "
        <details>
  <summary><h1>$muscle_group</h1></summary>
        <table style='width:100%'>
        <tr>
        <th>
        Add Set
        <br>
        <form method='post' action='?muscle_g=$muscle_group'>
        <select name='ex' id='ex' style = 'font-size:20px;'>
        $html_ex_select
        </select>
        <br>
        <label for='weight'>weight:</label>
        <input type='number' id='weight' name='weight' value='0' min = '1'>
        <br>
        <label for='reps'>reps:</label>
        <input type='number' id='reps' name='reps' value='0' min = '1'><br>  
        <input type='submit' value='Submit' style = 'font-size:20px;'> 
        </form>      
        <br>
        <br>
        <br>
        </tr>
        </table>   
        ";
        //Get Topset for echo Exercise per Muscle Group select Top 1 from latest 5
        /*notlikethis
        $sql = "select Sets.ID, 
                Reps, 
                Weight,
                Volume,
                Workout_ID, 
                Primary_Muscle, 
                Name, 
                Exercises.ID                
                From Sets                 
                INNER JOIN Exercises                 
                ON Sets.Exercise_ID = Exercises.ID    
                WHERE Primary_Muscle = '$muscle_group'
                ORDER BY Volume
                DESC;";
        */
        //get exercies perfmored 
        $sql = "Select DISTINCT Exercises.ID 
        From Workouts 
        INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID 
        INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID 
        WHERE Workouts.User_ID = '$user_id' AND Exercises.Primary_Muscle = '$muscle_group';";
        $result = NULL;
        $result = mysqli_query($conn, $sql);
        $ex_perforemd = NULL;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ex_perforemd[] = $row['ID'];
            }
        } else {
            $ex[] = "error";
        }
        //Get Top Set for each Exercis perfmored
        $table = "
        <table style='width:100%'>
        <tr>
            <th>Name</th>
            <th>R.</th>
            <th>W.</th>
            <th>V.</th>
            <th>Date</th>
        </tr>";
        foreach ($ex_perforemd as $ex) {
            $topset = Null;
            $result = Null;
            $sql = "Select Exercises.Name,
              Workouts.Date, 
              Workouts.ID, 
              Sets.Reps, 
              Sets.Weight, 
              Sets.Volume,
              Exercises.ID 
              From Workouts 
              INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID 
              INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID 
              WHERE Workouts.User_ID = '$user_id' AND Exercises.ID = '$ex'
              Order BY Volume DESC 
              LIMIT 1;";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $topset[] = $row;
                }
            } else {
                $ex[] = "error";
            }
            if ($topset != NULL) {
                foreach ($topset as $set) {
                    //var_dump($set);
                    if ('MAX(Sets.Volume)' != 0) {
                        $table = $table . "<tr>";
                        $table = $table . "<td>" . $set['Name'] . "</td>";
                        $table = $table . "<td>" . $set['Reps'] . "</td>";
                        $table = $table . "<td>" . $set['Weight'] . "</td>";
                        $table = $table . "<td>" . $set['Volume'] . "</td>";
                        $table = $table . "<td>" . $set['Date'] . "</td>";
                        $table = $table . "
                   </tr>";
                    }
                }
            }
        }
        $table = $table . "
        </table>";
        echo "Top Sets: $muscle_group";
        echo $table;
        echo "        </details>";
    }
    mysqli_close($conn);
    ?>
  <a href="workouts.php">Back</a> <br>
</body>

</html>
