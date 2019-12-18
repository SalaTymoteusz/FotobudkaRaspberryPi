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

    if ($_GET["user_id"]){
        $user_id = $_GET["user_id"];


    $sql = "SELECT session_id , session_name FROM sessions WHERE session_user_id = " .  $user_id;
    $result = $mysql->query($sql);
    $datas = array();
        while($row = $result->fetch_assoc()){

            $data['session_id'] = $row['session_id'];
            $data['session_name'] = $row['session_name'];
            array_push($datas, $data);
        }


        echo json_encode($datas);
        return json_encode($datas);
}else{
        $response = array();
        $response['MESSAGE'] = 'INVALID REQUEST';
        $response['STATUS'] = 400;

        echo json_encode($response);

    }

}



