<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
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
	//var_dump($_FILES['photo']);
	//var_dump($_FILES['photo']['tmp_name']);
	//var_dump($_FILES['photo']['name']);


        if ($_POST['session'] && $_POST['series_code'] && $_FILES['photo']['tmp_name']){
            
            $tmp_file = file_get_contents($_FILES['photo']['tmp_name']);
            $photo_name = $_FILES['photo']['name'];
            $series_code = $_POST['series_code'];
            $session = $_POST['session'];
            
              $sql_select_session = "SELECT session_id FROM sessions WHERE session_name ='" . $session . "'";
              var_dump($sql_select_session);
              $result = $mysql->query($sql_select_session);
              var_dump($result);
              $row =  $result->fetch_assoc();
                $session_id = $row['session_id'];
            
             if (!empty($row)){ // != false - nie ma zadnego
                 
                 $sql_insert_series = "INSERT INTO series (series_code) VALUES ('{$series_code}')";
                $sql_select_series = "SELECT series_id FROM series WHERE series_code ='" . $series_code . "'";
              var_dump($sql_select_series);
              $result = $mysql->query($sql_select_series);
              var_dump($result);
              $row =  $result->fetch_assoc();
                $series_id = $row['series_id'];
                 
                  $sql_insert_series_to_session = "INSERT INTO series_to_session (session_id. series_id) VALUES ({$session_id}, {$series_id})";
                  $upload_dir= "./sessions" .  "/" . $session . "/" . $series_code . "/" . $photo_name;
                 
                if (!file_exists("./sessions" .  "/" . $session . "/" . $series_code)) {
                mkdir("./sessions" .  "/" . $session . "/" . $series_code, 0777, true);
            }
                 
            $sql = "INSERT INTO Photos (name, series_code, series_id) VALUES ('{$photo_name}', '{$_POST['series_code']}', {$series_id})";
            //echo $sql;

            if(file_put_contents($upload_dir, $tmp_file)){
                $upload_status = true;
                echo 'Pomyśnie zuploadowano plik' . PHP_EOL;
            }else{
                echo 'Plik nie został pomyślnie zuploadowany :(' . PHP_EOL;
            }
            if($mysql->query($sql)){
                $sql_status = true;
                echo 'Wpis do bazy danych wykonał się pomyślnie'  . $sql . PHP_EOL;
            }else{
                echo 'Błąd w zapytaniu SQL - ' . $sql . PHP_EOL;
            }
                 
                $response['MESSAGE'] = 'UPLOAD SUCCED';
                $response['STATUS'] = 200;
             
            }else{
            
            
                $response['MESSAGE'] = 'SESSION DOESNT EXIST';
                $response['STATUS'] = 400; 
             }


        if ( !$_POST['session'] && $_POST['series_code'] && $_FILES['photo']['tmp_name']){
        
        $tmp_file = file_get_contents($_FILES['photo']['tmp_name']);
        $photo_name = $_FILES['photo']['name'];
        $series_code = $_POST['series_code'];
        $upload_dir= "./photos" .  "/" . $series_code . "/" . $photo_name;
        
        $upload_status = false;
        $sql_status = false;
        
        $sql_insert_series = "INSERT INTO series (series_code) VALUES ('{$series_code}')";
        
        $mysql->query($sql_insert_series);
        
        if (!file_exists("./photos" .  "/" . $series_code)) {
         mkdir("./photos" .  "/" . $series_code, 0777, true);
}
        
        
        
        $sql_select_series = "SELECT series_id FROM series WHERE series_code ='" . $series_code . "'";
        $result = $mysql->query($sql_select_series);
        var_dump( $sql_select_series);
        var_dump( $result);
        $row =  $result->fetch_assoc();
        
         
        $sql = "INSERT INTO Photos (name, series_code, series_id) VALUES ('{$photo_name}', '{$_POST['series_code']}', {$row['series_id']})";
        //echo $sql;
        
        if(file_put_contents($upload_dir, $tmp_file)){
             $upload_status = true;
             echo 'Pomyśnie zuploadowano plik' . PHP_EOL;
        }else{
            echo 'Plik nie został pomyślnie zuploadowany :(' . PHP_EOL;
        }
        if($mysql->query($sql)){
            $sql_status = true;
            echo 'Wpis do bazy danych wykonał się pomyślnie'  . $sql . PHP_EOL;
        }else{
            echo 'Błąd w zapytaniu SQL - ' . $sql . PHP_EOL;
        }
        
        if($upload_status && $sql_status){
            $response['MESSAGE'] = 'UPLOAD SUCCED';
            $response['STATUS'] = 200;
        }else{
            $response['MESSAGE'] = 'UPLOAD FAILED';
            $response['STATUS'] = 404;
        }
        }else{    
        $response['MESSAGE'] = 'INVALID REQUEST';
        $response['STATUS'] = 400;
        }

}
    echo json_encode($response);
}
?>
