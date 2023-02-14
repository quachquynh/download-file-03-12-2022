<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;

class Images extends Controller {

	public function pngToJpeg() {
		$filePath = ROOTPATH.'\\photos\\';
		$filePathDes = ROOTPATH.'\\photos\\';
		$image = imagecreatefrompng($filePath);
		$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
		imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
		imagealphablending($bg, TRUE);
		imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
		imagedestroy($image);
		$quality = 50; // 0 = worst / smaller file, 100 = better / bigger file 
		imagejpeg($bg, $filePathDes . ".jpg", $quality);
		imagedestroy($bg);
	}

}