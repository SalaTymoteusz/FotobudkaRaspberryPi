<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: X-Requested-With");

define("SERVER", "localhost");
define("USER", "tomasz");
define("PASSWORD", "1qaZXsw2");
define("DB", "fotobudka");

function delete_files($target)
{
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK);

        foreach ($files as $file) {
            delete_files($file);
        }
        rmdir($target);

        return true;
    } elseif (is_file($target)) {
        unlink($target);
        return true;
    }
}


$mysql = new mysqli(SERVER,USER,PASSWORD,DB);
$response = array();
if($mysql->connect_error){
    $response["MESSAGE"] = "SERVER ERROR - CANNOT CONNECT WITH DB";
    $response["STATUS"] = 500;
}else{
    echo 'polaczono z baza' . PHP_EOL;


    if ($_POST['session_id']){
        $session_id = $_POST['session_id'];
        $sql = 'SELECT session_name FROM sessions where session_id ='.  $session_id;
//        var_dump($sql);
        $result = $mysql->query($sql);
        $row = $result->fetch_assoc();
        $session_name =  $row['session_name'];

    if (!empty($row['session_name'])){

        $dir = './sessions/' . $session_name . '/';

        if (delete_files($dir)) {
           $sql = "DELETE FROM sessions WHERE session_id =". $session_id;
           $mysql->query($sql);

           $sql = "DELETE FROM series_to_session WHERE session_id =". $session_id;
           $mysql->query($sql);
//           echo 'usunieto sesje';
       }

        }else{
        $response['MESSAGE'] = 'SESSION DOESNT EXIST';
        $response['STATUS'] = 400;

        }
    }else{
        $response['MESSAGE'] = 'INVALID REQUEST';
        $response['STATUS'] = 400;
    }


    echo json_encode($response);
}
