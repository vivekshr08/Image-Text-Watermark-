<?php
require_once('waterMarkFunctions.php');
//Replace Item function 
function replaceItems($data)
{
	$items = '';
	$items = preg_replace('/rgb/is', '', $data);
	$items = preg_replace('/\(/is', '', $items);
	$items = preg_replace('/\)/is', '', $items);
	return trim($items);
} 

if(count($_POST)>0 && trim($_POST['imgname'])!='')
{
	//print_r($_POST);
	
	$format='';
	$fileext = explode('.',trim($_POST['imgname']));
	$formatext = strtoupper($fileext[1]);
	if($formatext=='JPG' || $formatext=='JPEG')
	{
		$format='JPEG';
	} 
	else if($formatext=='PNG')
	{
		$format='PNG';
	} else {
		echo "Not Supported File";
		exit();
		die;	
	}
	
	$SourceFile = trim($_POST['imgname']);
	$DestinationFile = 'watermark/'.basename(trim($_POST['imgname'])); 
	
	/* Text Processing */
	$WaterMarkText = trim($_POST['text']);
	$newLine = trim($_POST['textlinechar']);
	$WaterMarkText = wordwrap($WaterMarkText, $newLine, "\n", false);
	/* Text Processing End */
	
	$font = trim($_POST['textfont']).'.ttf';
	$fontsize = trim($_POST['textsize']);
	
	/* Create color combinations */
	$fontcolor = trim($_POST['color']);
	$fclr = replaceItems($fontcolor); 
	$f_clr = explode(',',$fclr);
	/* Color combination end */
	
	/* Opacity, Angle, Text Alignment*/
		$opct=trim($_POST['textopacity']);
		$angl=trim($_POST['textangle']);;
		$textalignment=trim($_POST['textposition']);;
		
	/*
     * PARAMETER DESCRIPTION
     * (1) SOURCE FILE PATH
     * (2) OUTPUT FILE PATH
     * (3) THE TEXT TO RENDER
     * (4) FONT NAME -- MUST BE A *FILE* NAME
     * (5) FONT SIZE IN POINTS
     * (6) FONT COLOR AS A HEX STRING
     * (7) OPACITY -- 0 TO 100
     * (8) TEXT ANGLE -- 0 TO 360
     * (9) TEXT ALIGNMENT CODE -- POSSIBLE VALUES ARE 11, 12, 13, 21, 22, 23, 31, 32, 33
     */
	
	$result = process_image_upload($SourceFile,$WaterMarkText,$font,$fontsize,$f_clr,$opct,$angl,$textalignment);
	if ($result === false) {
    	echo '<br>An error occurred during file processing.';
	} else {
    	echo $result;
	}
}
?>