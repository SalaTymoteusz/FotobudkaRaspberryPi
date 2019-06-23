<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

define("SERVER", "localhost");
define("USER", "id9844448_fotobudka");
define("PASSWORD", "fotobudka");
define("DB", "id9844448_fotobudka");

$mysql = new mysqli(SERVER,USER,PASSWORD,DB);

$response = array();

if($mysql->connect_error){
    echo "SERVER ERROR - CANNOT CONNECT WITH DB";
}else{

$code_from_request = $_GET["series_code"];

$sql = 'SELECT series_id FROM series where series_code ="'.  $code_from_request . '"';

$result = $mysql->query($sql);
$row = $result->fetch_assoc();


$sql = 'SELECT id, name, series_id FROM Photos where series_id ='.  $row['series_id'];
$result = $mysql->query($sql);
//$row = $result->fetch_assoc();

$datas = array();

while($row = $result->fetch_assoc()){
$photo_url = 'https://fotobudkaraspberry.000webhostapp.com/photos/' .  $code_from_request . "/" . $row['name'];
$data['url'] = $photo_url;
$data['id'] = $row['id'];
$data['album_id'] = $row['series_id'];
//echo json_encode($data);
//return json_encode($data);($datas, $data);
array_push($datas, $data);
}

echo json_encode($datas);
return json_encode($datas);

}

