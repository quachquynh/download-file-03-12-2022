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
}