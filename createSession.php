<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: X-Requested-With");

define("SERVER", "localhost");
define("USER", "tomasz");
define("PASSWORD", "1qaZXsw2");
define("DB", "fotobudka");

$mysql = new mysqli(SERVER,USER,PASSWORD,DB);
$response = array();
if($mysql->connect_error){
    $response["MESSAGE"] = "SERVER ERROR - CANNOT CONNECT WITH DB";
    $response["STATUS"] = 500;
}else{
        echo 'polaczono z baza' . PHP_EOL;

	var_dump($_POST);    

     if ($_POST['session'] && $_POST['user_id']){
        $session = $_POST['session'];
        $user_id = $_POST['user_id'];
         
         
        $sql_select_session = "SELECT session_id FROM sessions WHERE session_name ='" . $session . "'";
        $result = $mysql->query($sql_select_session);
        $row =  $result->fetch_assoc();
         if (!empty($row)){ // != false - nie ma zadnego
             
              $response['MESSAGE'] = 'SESSION ALREADY EXIST';
              $response['STATUS'] = 404;
             
         }else{
                $sql_insert_session = "INSERT INTO sessions (session_name, session_user_id) VALUES ('{$session}', '{$user_id}')";
                $mysql->query($sql_insert_session);
                
                 if (!file_exists("./sessions" .  "/" . $session)) {
                mkdir("./sessions" .  "/" . $session, 0777, true);
                 }
             
                $response['MESSAGE'] = 'SUCCESS';
                $response['STATUS'] = 200;
             }
     }else{
            $response['MESSAGE'] = 'INVALID REQUEST';
            $response['STATUS'] = 400;
	    $response['POST'] = $_POST['session'];
        }
    
    
echo json_encode($response);
}
