<?php
error_reporting(0);
/*
 * PHP function to text-watermark an image
 * Writes the given text on a GD image resource using
 * the specified true-type font, size, color, opacity, alignment, angle, position etc
 */

define('WATERMARK_MARGIN_ADJUST', 5);
define('WATERMARK_FONT_REALPATH', 'c:\\windows\\fonts\\');

function render_text_on_gd_image(&$source_gd_image, $text, $font, $size, $color, $opacity, $rotation, $align)
{
    $source_width = imagesx($source_gd_image);
    $source_height = imagesy($source_gd_image);
    $bb = imagettfbbox_fixed($size, $rotation, $font, $text);
    $x0 = min($bb[0], $bb[2], $bb[4], $bb[6]) - WATERMARK_MARGIN_ADJUST;
    $x1 = max($bb[0], $bb[2], $bb[4], $bb[6]) + WATERMARK_MARGIN_ADJUST;
    $y0 = min($bb[1], $bb[3], $bb[5], $bb[7]) - WATERMARK_MARGIN_ADJUST;
    $y1 = max($bb[1], $bb[3], $bb[5], $bb[7]) + WATERMARK_MARGIN_ADJUST;
    $bb_width = abs($x1 - $x0);
    $bb_height = abs($y1 - $y0);
    switch ($align) {
        case 11:
            $bpy = -$y0;
            $bpx = -$x0;
            break;
        case 12:
            $bpy = -$y0;
            $bpx = $source_width / 2 - $bb_width / 2 - $x0;
            break;
        case 13:
            $bpy = -$y0;
            $bpx = $source_width - $x1;
            break;
        case 21:
            $bpy = $source_height / 2 - $bb_height / 2 - $y0;
            $bpx = -$x0;
            break;
        case 22:
            $bpy = $source_height / 2 - $bb_height / 2 - $y0;
            $bpx = $source_width / 2 - $bb_width / 2 - $x0;
            break;
        case 23:
            $bpy = $source_height / 2 - $bb_height / 2 - $y0;
            $bpx = $source_width - $x1;
            break;
        case 31:
            $bpy = $source_height - $y1;
            $bpx = -$x0;
            break;
        case 32:
            $bpy = $source_height - $y1;
            $bpx = $source_width / 2 - $bb_width / 2 - $x0;
            break;
        case 33;
            $bpy = $source_height - $y1;
            $bpx = $source_width - $x1;
            break;
    }
    $alpha_color = imagecolorallocatealpha(
        $source_gd_image,
        $color[0],
        $color[1],
        $color[2],
        127 * (100 - $opacity) / 100
    );
    return imagettftext($source_gd_image, $size, $rotation, $bpx, $bpy, $alpha_color, WATERMARK_FONT_REALPATH . $font, $text);
}

/*
 * Fix for the buggy imagettfbbox implementation in gd library
 */

function imagettfbbox_fixed($size, $rotation, $font, $text)
{
    $bb = imagettfbbox($size, 0, WATERMARK_FONT_REALPATH . $font, $text);
    $aa = deg2rad($rotation);
    $cc = cos($aa);
    $ss = sin($aa);
    $rr = array();
    for ($i = 0; $i < 7; $i += 2) {
        $rr[$i + 0] = round($bb[$i + 0] * $cc + $bb[$i + 1] * $ss);
        $rr[$i + 1] = round($bb[$i + 1] * $cc - $bb[$i + 0] * $ss);
    }
    return $rr;
}

/*
 * Wrapper function for opening file, calling watermark function and saving file
 */

define('WATERMARK_OUTPUT_QUALITY', 90);

function create_watermark_from_string($source_file_path, $output_file_path, $text, $font, $size, $color, $opacity, $rotation, $align)
{
    list($source_width, $source_height, $source_type) = getimagesize($source_file_path);
    if ($source_type === NULL) {
        return false;
    }
    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_file_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_file_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_file_path);
            break;
        default:
            return false;
    }
    render_text_on_gd_image($source_gd_image, $text, $font, $size, $color, $opacity, $rotation, $align);
    imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);
    imagedestroy($source_gd_image);
}

/*
 * Uploaded file processing function
 */

define('UPLOADED_IMAGE_DESTINATION', 'images/');
define('PROCESSED_IMAGE_DESTINATION', 'watermark/');

function process_image_upload($Field,$WaterMarkText,$font,$fontsize,$f_clr,$opct,$angl,$textalignment)
{
	
    $temp_file_path = $Field;
    $temp_file_name = $Field;
    list(, , $temp_type) = getimagesize($temp_file_path);
    if ($temp_type === NULL) {
        return false;
    }
    switch ($temp_type) {
        case IMAGETYPE_GIF:
            break;
        case IMAGETYPE_JPEG:
            break;
        case IMAGETYPE_PNG:
            break;
        default:
            return false;
    }
    $uploaded_file_path = $temp_file_name;
    $processed_file_path = PROCESSED_IMAGE_DESTINATION . preg_replace('/\\.[^\\.]+$/', '.jpg', basename($temp_file_name));
    
	//move_uploaded_file($temp_file_path, $uploaded_file_path);
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
    $result = create_watermark_from_string(
        $uploaded_file_path,
        $processed_file_path,
        $WaterMarkText,
        $font,
        $fontsize,
        $f_clr,
        $opct,
        $angl,
        $textalignment
    );
    if ($result === false) {
        return false;
    } else {
        //return array($uploaded_file_path, $processed_file_path);
		return $processed_file_path;
    }
}
?>
