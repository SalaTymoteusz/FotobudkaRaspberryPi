<?php
define("SERVER", "mysql.wmi.amu.edu.pl");
define("USER", "fotobudka");
define("PASSWORD", "monsbaldinglyho");
define("DB", "fotobudka");

$mysql = new mysqli(SERVER,USER,PASSWORD,DB);

$response = array();

if($mysql->connect_error){
    $response["MESSAGE"] = "SERVER ERROR";
    $response["STATUS"] = 500;
}else{
	echo 'poloczono z baza';
    if (is_uploaded_file($_FILES['photo']['tmp_name']) && $_POST['code']){
        $tmp_file = $_FILES['photo']['tmp_name'];
        $photo_name = $_FILES['photo']['name'];
        $upload_dir= "./photos/" . $photo_name;
        $sql = "INSERT INTO Photos (name, code) VALUES ('{$photo_name}', {$_POST['code']})";
        if(move_uploaded_file($tmp_file, $upload_dir) && $mysql->query($sql)){
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

?>
