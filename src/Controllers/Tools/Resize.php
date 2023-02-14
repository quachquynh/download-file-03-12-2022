<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;

class Resize extends Controller {

	public function resize() {
		$current_folder = getcwd().'\\photos';
		$images = scandir($current_folder);

		foreach($images as $image) {
			$img = $current_folder.'\\'.$image;
			$filesize = filesize($img);
			if($filesize < 10000) {
				
			}
			else {
				echo $img.'<br/>';
				$result = resize_image($img);
				if($result) {
					unlink($img);
				}
			}
		}

	}

	public function resize02($newwidth) {
		$folder = ROOTPATH.'\\photos\\1\\';
		$images = scandir($folder);
		foreach($images as $image) {
			$img = $folder.$image;
			$filesize = filesize($img);
			if($filesize < 10000) {
				
			}
			else {
				resize_image($img, $newwidth);
			}
			
		}
		
	}

	public function watermark() {
		$folder = ROOTPATH.'\\photos\\1\\';
		$images = scandir($folder);
		foreach($images as $image) {
	        $img = $folder.$image;
	        $name = $image;
	        $watermark = ROOTPATH.'\\photos\\vietmymedia.png';
	        $img = $folder.$image;
			$filesize = filesize($img);
	        if($filesize < 10000) {
				
			}
			else {
				watermark_image($img, $watermark, $name);
			}
	        
	    }
		//$target = ROOTPATH.'\\resize\\banner1.jpg';
		//$wtrmrk_file = ROOTPATH.'\\photos\\vietmymedia.png';
		//$newcopy = ROOTPATH.'\\resize\\banner2.jpg';
		//watermark_image($target, $wtrmrk_file, $newcopy);
	}
}