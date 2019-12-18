<?php


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

define("SERVER", "localhost");
define("USER", "tomasz");
define("PASSWORD", "1qaZXsw2");
define("DB", "fotobudka");

$mysql = new mysqli(SERVER, USER, PASSWORD, DB);
$response = array();
if ($mysql->connect_error) {
    $response["MESSAGE"] = "SERVER ERROR - CANNOT CONNECT WITH DB";
    $response["STATUS"] = 500;
} else {
//    echo 'polaczono z baza' . PHP_EOL;


    if ($_POST['session_id'] ){

        $session_id = $_POST["session_id"];

        $sql = "UPDATE photobooth SET photobooth_active_session_id = {$session_id} WHERE photobooth_id = 1";

        if($mysql->query($sql)){
            $sql_status = true;
            echo 'Update bazy danych wykonał się pomyślnie '  . $sql . PHP_EOL;
        }else{
            echo 'Błąd w zapytaniu SQL - ' . $sql . PHP_EOL;
        }

    }else{
        echo 'INVALID REQUEST';
    }

echo json_encode($response);
}

