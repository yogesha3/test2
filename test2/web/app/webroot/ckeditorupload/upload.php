<?php

$url = '../img/uploads/'.time()."_".$_FILES['upload']['name'];
    if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name'])) )
    {
       $message = "No file uploaded.";
    }
    else if ($_FILES['upload']["size"] == 0)
    {
       $message = "The file is of zero length.";
    }
    else if (($_FILES['upload']["type"] != "image/pjpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png"))
    {
       echo $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
       exit;
    }
    else if (!is_uploaded_file($_FILES['upload']["tmp_name"]))
    {
       echo $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
       exit;
    }
    else {
      $message = "";
      $move = @ move_uploaded_file($_FILES['upload']['tmp_name'], $url);
      if(!$move)
      {
         echo $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
         exit;
      }
      $url = explode('../',$url);
      if($_SERVER['HTTP_HOST'] == 'localhost')
      {
        $serverAddr = 'http://'.$_SERVER['HTTP_HOST'].'/foxhopr/';
      }elseif($_SERVER['HTTP_HOST'] == '10.10.10.174')
      {
        $serverAddr = 'http://'.$_SERVER['HTTP_HOST'].'/jitendra/b2b_portal/';
      }
      else
      {
        $serverAddr = 'http://103.231.222.21/foxhopr/';
      }
      $url = $serverAddr.$url[1];
    }
$funcNum = $_GET['CKEditorFuncNum'] ;
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
?>
