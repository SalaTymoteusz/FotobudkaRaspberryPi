<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
define("SERVER", "localhost");
define("USER", "tomasz");
define("PASSWORD", "1qaZXsw2");
define("DB", "fotobudka");
header('Content-type: application/json');


$mysql = new mysqli(SERVER,USER,PASSWORD,DB);
$response = array();
if($mysql->connect_error){
    echo "SERVER ERROR - CANNOT CONNECT WITH DB";
}else{
if ($_GET["series_code"]){
$code_from_request = $_GET["series_code"];
    
       
    

$sql = 'SELECT series_id FROM series where series_code ="'.  $code_from_request . '"';
$result = $mysql->query($sql);
$row = $result->fetch_assoc();
$series_id = $row['series_id'];
    
    
$sql = 'SELECT series_id, session_id FROM series_to_session where series_id ='.  $series_id;
//var_dump($sql);
$result = $mysql->query($sql);
$row = $result->fetch_assoc();
$session_id =  $row['session_id'];
    
$session = false;
    
if (!empty($row['series_id'])){
 $session = true;
     
        $sql = 'SELECT session_name FROM sessions where session_id ='.  $row['session_id'];
        $result = $mysql->query($sql);
        $row = $result->fetch_assoc();
        $session_name= $row['session_name'];
     
}

$sql = 'SELECT series_code, name, series_id, photo_id FROM Photos where series_id ='.  $series_id;
//    var_dump($sql);
$result = $mysql->query($sql);
//$row = $result->fetch_assoc();
$datas = array();
while($row = $result->fetch_assoc()){
    

if ($session == false){
$photo_url = 'http://fotobudkaraspberry.pl/photos/' . "" .  $code_from_request . "/" . $row['name'];
}else{
    $photo_url = 'http://fotobudkaraspberry.pl/sessions/'  .  $session_name . "/" . $row['series_code'] . "/" . $row['name'];
}
$data['url'] = $photo_url;
$data['id'] = $row['photo_id'];
$data['album_id'] = $row['series_id'];
//echo json_encode($data);
//return json_encode($data);($datas, $data);
array_push($datas, $data);
}
echo json_encode($datas);
return json_encode($datas);
}
    else if ( $_GET["session_id"]){
        
        $session_id = $_GET["session_id"];
        
        
        $sql = 'SELECT DISTINCT series_id FROM series_to_session where session_id ="'.  $session_id . '"';
        $result = $mysql->query($sql);
        
        $series = array();
        while($row = $result->fetch_assoc()){
//        var_dump($row['series_id']);
        $serial['series_id']  =  $row['series_id'];
        //echo json_encode($data);
        //return json_encode($data);($datas, $data);
        array_push($series, $serial);
            
        }
//        var_dump($series);
        $datas = array();
        
        foreach ($series as $serial){
            $sql = 'SELECT series_code, name, series_id, photo_id FROM Photos where series_id ='.  $serial['series_id'];
            $result = $mysql->query($sql);
            while($row = $result->fetch_assoc()){
            $photo_url = 'http://fotobudkaraspberry.pl/sessions/' . $session . "/" .  $row['series_code'] . "/" . $row['name'];
            $data['url'] = $photo_url;
            $data['id'] = $row['photo_id'];
            $data['album_id'] = $row['series_id'];
            //echo json_encode($data);
            //return json_encode($data);($datas, $data);
            array_push($datas, $data);
        }
            
        }
        echo json_encode($datas);
        return json_encode($datas);
     
            echo "SERVER ERROR - INVALID REQUEST";
    }else{
        
    }
}
