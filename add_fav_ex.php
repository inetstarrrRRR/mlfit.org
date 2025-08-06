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
        $query = "Insert INTO Fav_Ex (User_ID, Exercise_ID)
        VALUES (?,?);";
        if (mysqli_execute_query($conn, $query, [$user_id, $x_selected])) {
            unset($_POST);
        }
    }
    echo "Favourtie Exercies: <br>";
    $query = "Select Name, Fav_Ex.ID
        FROM Fav_Ex 
        INNER JOIN Exercises ON Fav_Ex.Exercise_ID = Exercises.ID 
        WHERE User_ID = ?";
    $result = mysqli_execute_query($conn, $query, [$user_id]);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            //var_dump($row);
            $fav_ex[] = $row;
        }
    } else {
        echo "user not found";
    }
    if ($fav_ex) {
        $table = "
                   <table style='width:100%'>
                   <tr>
                       <th>Name</th>
                       <th>Delete</th>
                   </tr>";
        foreach ($fav_ex as $ex) {
            $table = $table . "
                            <tr>
                            ";
            $table = $table . "<td>" . $ex['Name'] . "</td>";
            $table = $table . "<td>" . "<form action='delete_fav.php' method='post'>
             <input type='submit' name='" . $ex['ID'] . "' value='X'/>
         </form>" . "</td>";
            $table = $table . "
                           </tr>";
        }
        $table = $table . "
                       </table>";
        echo $table;
    }
    $query = "SELECT DISTINCT Primary_Muscle FROM Exercises;";
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
        $fav_ex_name = array_column($fav_ex, 'Name');
        foreach ($ex as $x) {
            if (in_array($x['Name'], $fav_ex_name)) {

            } else {
                $name = $x['Name'];
                $id = $x['ID'];
                $html_ex_select = $html_ex_select . "<option value='$id'>$name</option>";
            }
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

    ?>
    <a href="workouts.php">Back</a> <br>
</body>

</html>