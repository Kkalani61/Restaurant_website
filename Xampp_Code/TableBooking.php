<?php
    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        $user_email = $_SESSION['user_email'];
        $loggedin = true;
    }
    else
    {
        header("Location: Login.php");
    }

    require "partials/_dbconnect.php";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST)) {
            $data['res'] = false;
            $date = $_POST['date'];
            $time = $_POST['time'];
            // echo $time . " " . $date;
            $table_num = (int)$_POST['table_num'];

            $find = "SELECT * FROM RESERVATION WHERE res_table_num = $table_num AND DATE_SUB(res_time, INTERVAL 30 MINUTE) < TIME('$time') AND end_time > TIME('$time') AND res_date = DATE('$date')";
            $find_res = mysqli_query($conn, $find);
            if(!$find_res) {
                echo "Find Error " . mysqli_connect_error($conn);
            }
            $num_rows = mysqli_num_rows($find_res);
            if($num_rows == 0) {
                $insert = "INSERT INTO RESERVATION(res_user_email, res_table_num, res_date, res_time, end_time) VALUES('$user_email', $table_num, DATE('$date'), TIME('$time'), DATE_ADD(TIME('$time'), INTERVAL 2 HOUR))";
                $insert_res = mysqli_query($conn, $insert);
                if(!$insert_res) {
                    echo "Insert Error " . mysqli_connect_error($conn);
                }
                else {
                    $data['res'] = true;
                }
            }
            echo json_encode($data);
        }
    }
?>