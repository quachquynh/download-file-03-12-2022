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

		echo '<a href="'.ROOTURL.'/delete">Delete All</a>';
    	$domain = getDomain($url);
		$output = curl($url);
		
		$pattern_url = "/\b((?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]*))/i";

		$pattern_mp4 = '/((?!\/)([\/|\-|\_]|[0-9]|[a-zA-Z])*\.(mp4|avi))/i';

		$pattern_image = '/((?!\/)([\/|\-|\_]|[0-9]|[a-zA-Z])*\.(jpg|png))/i';

		/*if(preg_match('/jjgir/', $domain)) {
			preg_match_all($pattern_image, $output, $matches);
			foreach ($matches[0] as $value) {
				$image_link = $domain.$value;
				
				if(preg_match('/hd\-|.*cute.*|\.png|^\d/', $image_link)) {
					echo '';
				}
				else 
				{
					$path = parse_url($image_link);
					$pathinfo = pathinfo($path["path"]);
					$image_name = $pathinfo["basename"];
					$get_data = curl($image_link);

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
		} */


		/*if(preg_match_all($pattern_mp4, $output, $matches)) {
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
		}*/

		if(preg_match_all($pattern_image, $output, $matches)) {

			foreach ($matches[0] as $value) {

				$image_link = $domain.$value;
				list($width, $height) = getimagesize($image_link);

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
			} // end foreach
		}

		// elseif end
	}

	public function downloadfile() {

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(isset($_POST['btn-submit'])) {

				// Imput
			    $form_data = $_POST['form-data'];
			    $domain = trim($_POST['domain']);
			    $domain_link = trim($_POST['domain-link']);

			    // Regex
			    $pattern = "/\b(.*(\.css|\.js|\.jpg|\.png|\.webp|\.svg).*)/i";
			    preg_match_all($pattern, $form_data, $matches);

			    $p_http = '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';
			    preg_match_all($p_http, $form_data, $m_http);
			    
			    foreach($m_http[0] as $value) {
				    // Regex
				    $pattern = "/\b(.*(\.css|\.js|\.jpg|\.png|\.webp|\.svg).*)/i";
				    preg_match_all($pattern, $value, $m_file);
				    foreach($m_file[0] as $i) {
				    	//echo $i."<br/>";
				    	$exp = explode('/', $i);
				        
				        $fullUrl = $i;
				        				        // Folder
				        $folderPath = ROOTPATH.'\\public\\download\\';
				       	createFolderByPath($fullUrl, $folderPath);
				       	put_content( $fullUrl, $folderPath);
				    }
			    }

			    foreach ($matches[0] as $value) {
			    	if(preg_match('/hanoitv\.vn/',$domain)) {

				    	// hanoitv

				        $exp = explode('"', $value);

				        $exp = explode('/', $value);
				        $uri = '/'.$exp[1]. '/'.$exp[2];

				        // Remove ?
				        $exp_remove = explode('?', $uri);
				        $uri2 = $exp_remove[0];

				        // Remove "
				       	$exp2 = explode('"',$uri2);
				       	$fullUrl = $domain.$exp2[0];
				       	//echo $fullUrl.'<br/>';
				        // get file name
				        $uri_file = $exp[1];
				        $file = explode('/', $fullUrl);
				        $filename = end($file);
				        
				        // Folder
				        $folderPath = ROOTPATH.'\\public\\download\\';

				       	//createFolderByPath($fullUrl, $folderPath);
				    	//put_content( $fullUrl, $folderPath);
				    }
				    else 
				    {
			    		$data_string = $domain.$value;
			    		//$exp = explode('"', $data_string);
			    		$exp = explode("'", $data_string);
			    		// Remove ?
				        $exp_remove = explode('?', $exp[1]);
				        $uri2 = $exp_remove[0];

				        // haravan
				        //$fullUrl = 'https:'.$uri2;

			    		$fullUrl = $domain_link.$exp[2];
			    		$end = end($exp);
			    		unset($end);

			    		//var_dump($exp);

			    		$folderPath = ROOTPATH.'\\public\\download\\';
			    		//createFolderByPath($fullUrl, $folderPath);
			    		//put_content( $fullUrl, $folderPath);
			    	}
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

	public function download_image($url) {
		$data = curl($url);
		preg_match_all('/<video.*/', $data, $matches);
		$pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
		foreach($matches[0] as $value) {
			preg_match_all($pattern, $value, $m2);
			$url = $m2[0][0];
			$exp = explode('/', $url);
			$name = end($exp);
			$ch = curl_init($url);
			$fp = fopen('C:\\ffmpeg\\photos\\'.$name, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
		}
	}

	public function download_video($url) {
		$this->download_image($url);
		$data = curl($url);
		
		if(preg_match('/eporner/',$data)) {
			preg_match_all('/360p:.*/', $data, $matches);
			preg_match_all('/\/.*\.mp4/',$matches[0][0], $m2);
			$video = 'https://www.eporner.com'.$m2[0][0];
			$data_vi = curl($video);
			$url = $m2[0][0];
			$exp = explode('/', $url);
			$name = end($exp);
			//echo file_put_contents("public/download/".$name,$data_vi);
		}
		else {
			$pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

			preg_match_all($pattern, $data, $matches);
			foreach($matches[0] as $value) {
				$p2 = '/(http.*\.preview\.mp4\.jpg)/';
				preg_match_all($p2, $value, $m2);
				//echo $value.'<br/>';
				var_dump($m2);
			}
		}

	}
}