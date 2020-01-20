<?php
$dir = 'photos/';
if( $opendir = opendir($dir)){
	while(($file = readdir($opendir)) !== FALSE){
	 if($file!="."&& $file!=".."){
		$file_to_send = $file;
		}
	}
} 

$file = file_get_contents(__DIR__ . '/photos/' .$file_to_send);

$url = "https://fotobudka.projektstudencki.pl/uploadPhoto.php";
//$filename = $_FILES['file']['name'];
//$filedata = $file_to_send;
//$filesize = filesize($dir . $file_to_send);
//if ($filedata != '')
//{
//$post = array('file_contents'=> $file);
//    $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
    $postfields = array("code" => 'test321', "photo" => $file, 'name' => 'plikget.jpg' ,"catalog_id" => 1);
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => true,
        CURLOPT_POST => 1,
//        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $postfields,
//        CURLOPT_INFILESIZE => $filesize,
        CURLOPT_RETURNTRANSFER => true
    ); // cURL options
    curl_setopt_array($ch, $options);
    var_dump(curl_exec($ch));


//    if(!curl_errno($ch))
//    {
//        $info = curl_getinfo($ch);
//        if ($info['http_code'] == 200)
//            $errmsg = "File uploaded successfully";
//    }
//    else
//    {
//        $errmsg = curl_error($ch);
//    }
    curl_close($ch);
//}
//else
//{
//    $errmsg = "Please select the file";
//}