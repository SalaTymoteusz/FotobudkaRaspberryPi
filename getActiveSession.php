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
    echo 'polaczono z baza' . PHP_EOL;

    $sql = "SELECT photobooth_active_session_id FROM photobooth WHERE photobooth_id = 1";

    $result = $mysql->query($sql);
    $row =  $result->fetch_assoc();
    $active_session_id = $row['photobooth_active_session_id'];

    $data = array();

    $data['session_id'] = $active_session_id;

    var_dump($data);
    return json_encode($data);
}

