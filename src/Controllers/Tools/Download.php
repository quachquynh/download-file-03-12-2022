<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Models\Product;
use App\Core\DB;

class Download extends Controller {

	/*
	 * Use for eporner
	 */
	public function download($url) {

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

		echo '<a href="'.ROOTURL.'/delete">Delete All</a>';
    	$domain = getDomain($url);
		$output = curl($url);
		
		//$pattern_url = "/\b((?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]*))/i";

		$pattern_mp4 = '/((?!\/)([\/|\-|\_]|[0-9]|[a-zA-Z])*\.(mp4|avi))/i';

		$pattern_image = '/((?!\/)([\/|\-|\_]|[0-9]|[a-zA-Z])*\.(jpg|png))/i';

		//preg_match_all($url_pattern, $output, $matches);

		//foreach($matches[0] as $url_item) {
			//echo $url_item.'<br/>';
			/*
			 * Use for eporner
			 */
		    //$exp = explode('"', $url_item);
		    //$full_url = $domain.$exp[1];
		    //$data_url = curl($full_url);

		//}

		if(preg_match_all($pattern_mp4, $output, $matches)) {
			foreach ($matches[0] as $file) {
				$url_path = $domain.$file;
				if(preg_match('/720p/', $url_path)) {
					$exp = explode("/", $url_path);
					$filename = end($exp);
					$data = curl($url_path);
					var_dump($url_path);
					$result = file_put_contents(ROOTPATH.'\\public\\media\\'.$filename, $data);
				}
				
			}
		}
		elseif(preg_match_all($pattern_url, $output, $matches)) {
			var_dump($matches);
		}

		elseif(preg_match_all($pattern_image, $output, $matches)) {
			foreach ($matches[0] as $value) {
				$image_link = $domain.$value;
				
				list($width, $height) = getimagesize($image_link);
				var_dump($width);

				$path = parse_url($image_link);
				if(preg_match_all('/hd\-|.*cute.*|^\d/', $image_link, $m)) {
					echo '';
				}
				elseif($width < 600) {
					echo '';
				}
				else {
					$pathinfo = pathinfo($path["path"]);
					$image_name = $pathinfo["basename"];
					$get_data = curl($image_link);
					
					if(preg_match_all('/^\d/', $image_name, $m_name)) {
						echo '';
					}
					else {
						$folder = ROOTPATH."\\photos\\";
						if (!file_exists($folder)) {
							mkdir($folder, 0777);
							$result = file_put_contents($folder.$image_name, $get_data);
							if($result) {
								resize_run($folder);
							}
						}
						else {
							$result = file_put_contents($folder.$image_name, $get_data);
							if($result) {
								resize_run($folder);
							}
						}
					}
				}
			}
		}

		} // server method
		else {
			$this->view('download/form');
		}
		// elseif end
	}

	public function downloadfile() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['btn-submit'])) {
			    $form_data = $_POST['form-data'];

			    $domain = 'https://hanoitv.vn';
			    $data = curl($domain);

			    $d = explode('"', $form_data);
			    $pattern = "/\b(.*(\.css|\.js).*)/i";
			    preg_match_all($pattern, $form_data, $matches);

			    foreach ($matches[0] as $value) {

			        $exp = explode('"', $value);

			        $exp = explode('/', $value);
			        echo $exp[1]. '==>'.$exp[2].'<br/>';
			        // get file name
			        $uri_file = $exp[1];
			        $exp = explode('/', $uri_file);
			        $filename = end($exp);
			        

			        $url = $domain.$uri_file;
			        //var_dump($url);
			        //$url_data = curl($url);

			        $result = preg_replace('/\?v\=.*/', "", $url);
			        //var_dump($result);
			        //var_dump($url);

			        $folderPath = ROOTPATH.'\\public\\download\\';
			        //file_put_contents($folderPath.$filename, $url_data);
			        //foreach($exp[1] as $url) {
			        	
			            //var_dump($domain.$url);
			        //}
			        
			    }
				}
		}
		else {
			//$this->view('download/form');
			download_input();
		}
		
	}

	public function delete() {
		$folder = ROOTPATH."\\resize\\";
		$files = scandir($folder);
    	foreach($files as $file) {
	        $name = $folder.$file;
			$r = $folder.$name;
			unlink_file($r);
		}
	}

	function hls() {
		$data = curl("https://api.cdnz.workers.dev");
		var_dump($data);
	}
}