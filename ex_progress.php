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
    if (isset($_GET['muscle_g'])) {
        $x_selected = $_POST['ex'];
        $query = "SELECT Workouts.ID, 
      Workouts.Date, 
      Exercises.Name, 
      Sets.Reps, 
      Sets.Weight, 
      Sets.Volume
      FROM Workouts 
      INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID 
      INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID 
      WHERE Workouts.User_ID = ? AND Exercises.ID = ? 
      ORDER BY Sets.Weight DESC;";
        $result = mysqli_execute_query($conn, $query, [$user_id, $x_selected]);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ex_perforemd[] = $row;
            }
        }
        //Draw Table Orderd by Weight
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
            $table = $table . "<tr>";
            $table = $table . "<td>" . $ex['Name'] . "</td>";
            $table = $table . "<td>" . $ex['Reps'] . "</td>";
            $table = $table . "<td>" . $ex['Weight'] . "</td>";
            $table = $table . "<td>" . $ex['Volume'] . "</td>";
            $table = $table . "<td>" . $ex['Date'] . "</td>";
            $table = $table . "
                   </tr>";
        }
        $table = $table . "
        </table>";
        echo "Orderd by weight";
        echo $table;
           $query = "SELECT Workouts.ID, 
      Workouts.Date, 
      Exercises.Name, 
      Sets.Reps, 
      Sets.Weight, 
      Sets.Volume
      FROM Workouts 
      INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID 
      INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID 
      WHERE Workouts.User_ID = ? AND Exercises.ID = ? 
      ORDER BY Sets.Volume DESC;";
        $ex_perforemd = [];
        $result = mysqli_execute_query($conn, $query, [$user_id, $x_selected]);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ex_perforemd[] = $row;
            }
        }
        //Draw Table Orderd by Volume
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
            $table = $table . "<tr>";
            $table = $table . "<td>" . $ex['Name'] . "</td>";
            $table = $table . "<td>" . $ex['Reps'] . "</td>";
            $table = $table . "<td>" . $ex['Weight'] . "</td>";
            $table = $table . "<td>" . $ex['Volume'] . "</td>";
            $table = $table . "<td>" . $ex['Date'] . "</td>";
            $table = $table . "
                   </tr>";
        }
        $table = $table . "
        </table>";
        echo "Orderd by Volume";
        echo $table;
    } else {
        $query = $sql = "SELECT DISTINCT Primary_Muscle FROM Exercises;";
        $result = mysqli_execute_query($conn, $query, []);
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
            //$sql = "SELECT ID, Name FROM Exercises WHERE Primary_Muscle = '$muscle_group';";
            $query = "SELECT ID, Name FROM Exercises WHERE Primary_Muscle = ?;";
            $result = null;
            $ex = null;
            $result = mysqli_execute_query($conn, $query, [$muscle_group]);
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
        <input type='submit' value='Submit' style = 'font-size:20px;'> 
        </form>      
        <br>
        <br>
        <br>
        </tr>
        </table>   
        ";
            echo "        </details>";
        }
        mysqli_close($conn);
    }
    ?>
    <a href="workouts.php">Back</a> <br>
</body>

</html>