<?php
define("SERVER", "localhost");
define("USER", "id9844448_fotobudka");
define("PASSWORD", "fotobudka");
define("DB", "id9844448_fotobudka");

$mysql = new mysqli(SERVER,USER,PASSWORD,DB);

$response = array();

if($mysql->connect_error){
    $response["MESSAGE"] = "SERVER ERROR";
    $response["STATUS"] = 500;
}else{
	echo 'polaczono z baza';
	//var_dump($_FILES['photo']);
	//var_dump($_FILES['photo']['tmp_name']);
	//var_dump($_FILES['photo']['name']);
	
        echo 'zuploadowano plik';
        $tmp_file = file_get_contents($_FILES['photo']['tmp_name']);
        $photo_name = $_FILES['photo']['name'];
        $catalog_id = $_POST['catalog_id'];
        $upload_dir= "./photos" . "/" . $photo_name;
         
        $sql = "INSERT INTO Photos (name, code, catalog_id) VALUES ('{$photo_name}', '{$_POST['code']}', {$_POST['catalog_id']})";
        echo $sql;
        
        file_put_contents($upload_dir, $tmp_file);
         //file_put_contents($tmp_file, $photo_name);
        
        var_dump($mysql->query($sql));
        //     $response['MESSAGE'] = 'UPLOAD SUCCED';
        //     $response['STATUS'] = 200;


        // }else{
        //     $response['MESSAGE'] = 'UPLOAD FAILED';
        //     $response['STATUS'] = 404;

        // }

    // }else{
    //     $response['MESSAGE'] = 'INVALID REQUEST';
    //     $response['STATUS'] = 400;
    // }

echo json_encode($response);
echo 'test';
}
?>
