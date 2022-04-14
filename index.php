<?php
 //error_reporting(0);
 //error_reporting(E_ALL);
 ini_set('set_time_limit',0);
 ini_set('max_allowed_packet', "100M");
 ini_set('post_max_size', '10M');
 ini_set('upload_max_filesize', '10M');
 ini_set('max_input_time', 3000);
 ini_set('table_cache', 4000);
 session_start();
 $errMsg = '';
 //print_r($_POST);
 if($_SERVER['REQUEST_METHOD']=='POST')
 {
	 $fileext = explode('.',$_FILES['photo']['name']);
	 $formatext = strtoupper($fileext[1]);
	 
	 if($formatext=='PNG' || $formatext=='JPG' || $formatext=='JPEG')
	 {
		 $name = time().'_'.$_FILES['photo']['name'];
		 $tmp_name = $_FILES['photo']['tmp_name'];
		 $path = 'images/';
		 if(!empty($name))
		 {
			$upl = move_uploaded_file($tmp_name,$path.$name);
			$_SESSION['IMGPATH'] = $path.$name;
			if($upl)
			{
				header('location:imageprocessing.php');
			}
		 } else {
			$errMsg =  "Error! Please choose a file";
		 }
	 } else {
		 $errMsg =  "Error! Please choose jpeg/jpg/png images";
	 }
 }
 
 ?>
 
 <div style="width:55%; margin:0 auto; border:1px solid #333; z-index:999999">
 <div style="width:100%; margin:0 auto; text-align:center; font-style:italic;"><h2>Image Processing with text</h2></div>
 <div style="width:80%; margin:0 auto; padding:20px 5px 20px 5px; text-align:center;">
 <!-- Creating a form to upload image --> 
 <form id='uploadImage' action='' method='post' enctype='multipart/form-data'>
 <input type='file' name='photo' id="photo" />
 <button>Upload File</button>
 </form>
 </div>
 <!-- p element with id msg to display success msg --> 
 <p id='msg' style="text-align: center;"><?php echo ($errMsg!='')? $errMsg : '';?></p>
 </div>