<?php
define("SERVER", "localhost");
define("USER", "id9844448_fotobudka");
define("PASSWORD", "fotobudka");
define("DB", "id9844448_fotobudka");

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
	
        if ( $_POST['code'] && $_POST['catalog_id'] && $_FILES['photo']['tmp_name']){
        
        $tmp_file = file_get_contents($_FILES['photo']['tmp_name']);
        $photo_name = $_FILES['photo']['name'];
        $catalog_id = $_POST['catalog_id'];
        $upload_dir= "./photos" . "/" . $photo_name;
        
        $upload_status = false;
        $sql_status = false;
         
        $sql = "INSERT INTO Photos (name, code, catalog_id) VALUES ('{$photo_name}', '{$_POST['code']}', {$_POST['catalog_id']})";
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

echo json_encode($response);
}
?>
