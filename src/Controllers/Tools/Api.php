<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Core\DB;

class Api extends Controller {

	public function __construct() {
		$this->model = new DB();
	}

	public static function HTTPPost($url, $params) {
        $query = http_build_query($params);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function HTTPGet($url, $params) {
        $query = http_build_query($params); 
        $ch    = curl_init($url.'?'.$query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
	    curl_close($ch);
	    return $response;
    }

    public static function GetInfo($url, $params) {
	    $query = http_build_query($params); 
        $ch    = curl_init($url.'?'.$query);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, TRUE);
	    curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	    $data = curl_exec($ch);
	    $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	    $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    return [
	        'fileExists' => (int) $httpResponseCode == 200,
	        'fileSize' => (int) $fileSize
	    ];
	}

	public function getFileSize($remoteFile) {
		//URL of the remote file that you want to get
		//the file size of.

		//Create a cURL handle with the URL of
		//the remote file.
		$curl = curl_init($remoteFile);

		//Set CURLOPT_FOLLOWLOCATION to TRUE so that our
		//cURL request follows any redirects.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		//We want curl_exec to return  the output as a string.
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//Set CURLOPT_HEADER to TRUE so that cURL returns
		//the header information.
		curl_setopt($curl, CURLOPT_HEADER, true);

		//Set CURLOPT_NOBODY to TRUE to send a HEAD request.
		//This stops cURL from downloading the entire body
		//of the content.
		curl_setopt($curl, CURLOPT_NOBODY, true);

		//Execute the request.
		curl_exec($curl);

		//Retrieve the size of the remote file in bytes.
		$fileSize = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		return $fileSize;
	}

	public function params() {
		$token_folder = ROOTPATH."\\api-token\\";
		$token_path = $token_folder.'token.json';

		$token_json = file_get_contents($token_path);
		$token_arr = json_decode($token_json);
		$data_arr = $token_arr->data;
		$token = $data_arr->token;
		$params = [
			'token' => $token
		];
		return $params;	
	}

	public function request() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['btn-submit'])) {
				$email = $_POST['email'];
				$password = $_POST['password'];
				$params = [
					'email' => $email,
					'password' => $password,
				];
				$response = Api::HTTPPost('http://localhost/laravel/public/api/request', $params);
				if($response) {
					$token_folder = ROOTPATH."\\api-token\\";
					$token_path = $token_folder.'token.json';
					
					if (file_exists($token_path)){
					    $token = fopen($token_path, "w") or die("Unable to open file!");
					    fwrite($token, $response);
						fclose($token);
					}else{
					    $token = fopen($token_path, "w") or die("Unable to open file!");
					    fwrite($token, $response);
						fclose($token);
					}
				}
			}
		}
		else {
			$this->view('api/login');
		}
		
	}

	// Code chuan
	public function streamMp4Url() {
		$params = $this->params();
		$response = Api::HTTPGet('http://localhost/laravel/public/api/getdata', $params);
		$result = Api::GetInfo('http://localhost/laravel/public/api/getdata', $params);
		$size   = $result['fileSize']; // File size
		$length = $size;           // Content length
		$start  = 0;               // Start byte
		$end    = $size - 1;       // End byte
		header('Content-type: video/mp4');
		// Code php lo la do dong nay
		header('Accept-Ranges: bytes');
		//header("Accept-Ranges: 0-$length");
		if (isset($_SERVER['HTTP_RANGE'])) {
		    $c_start = $start;
		    $c_end   = $end;

		    list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
		    if (strpos($range, ',') !== false) {
		        header('HTTP/1.1 416 Requested Range Not Satisfiable');
		        header("Content-Range: bytes $start-$end/$size");
		        exit;
		    }
		    if ($range == '-') {
		        $c_start = $size - substr($range, 1);
		    }else{
		        $range  = explode('-', $range);
		        $c_start = $range[0];
		        $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
		    }
		    $c_end = ($c_end > $end) ? $end : $c_end;
		    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
		        header('HTTP/1.1 416 Requested Range Not Satisfiable');
		        header("Content-Range: bytes $start-$end/$size");
		        exit;
		    }
		    $start  = $c_start;
		    $end    = $c_end;
		    $length = $end - $start + 1;
		    fseek($fp, $start);
		    header('HTTP/1.1 206 Partial Content');
		}
		header("Content-Range: bytes $start-$end/$size");
		header("Content-Length: ".$length);
		echo $response;
	}

	public function getData($url) {
		
		$token_folder = ROOTPATH."\\api-token\\";
		$token_path = $token_folder.'token.json';

		$token_json = file_get_contents($token_path);
		$token_arr = json_decode($token_json);
		$data_arr = $token_arr->data;
		$token = $data_arr->token;
		$params = [
			'token' => $token
		];
		$response = Api::HTTPGet($url, $params);
			//echo $response;
		return $response;
		
	}


	public function parseHLS() {
		$filename = 'jp63';
		$url = ROOTPATH. '\\media\\'.$filename.'.m3u8';

		$url_data = 'http://localhost/laravel/public/api/getdata';
		$data = $this->getData($url_data);
		$exp = explode("\n", $data);
		$end = explode("\\", $url);
		$m3u8 = end($end);

		$folder = ROOTPATH.'\\public\\media\\';
		var_dump($exp);

		foreach($exp as $line) {
			if(preg_match('/\#.*\,/', $line)) {
				$content = $line.PHP_EOL;
				file_put_contents($folder.$m3u8, $content, FILE_APPEND | LOCK_EX);
			}
			elseif(preg_match('/jp.*\.ts/', $line)) {
				$content = $url_data.'/'.$line.PHP_EOL;
				file_put_contents($folder.$m3u8, $content, FILE_APPEND | LOCK_EX);
			}
			else {
				$content = $line.PHP_EOL;
				file_put_contents($folder.$m3u8, $content, FILE_APPEND | LOCK_EX);
			}
		}

	}

	// Code stream video from url
	public function getVideo() {
		$url = 'http://localhost/laravel/public/api/getfile/zed.mp4';
		$params = $this->params();
		$response = Api::HTTPGet($url, $params);
		$result = Api::GetInfo($url, $params);
	    $length = $result['fileSize'];
	    $start = 0;  
	    $end = $length - 1; 
	    header('Content-type: video/mp4');
	    header("Accept-Ranges: bytes");
	    
	    if (isset($_SERVER['HTTP_RANGE'])) {
	      	$c_start = $start;
	      	$c_end = $end;
	      	list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
	      	if (strpos($range, ',') !== false) {
	        header('HTTP/1.1 416 Requested Range Not Satisfiable');
	        header("Content-Range: bytes $start-$end/$size");
	        exit;
	      }
	      if ($range == '-') {
	        $c_start = $size - substr($range, 1);
	      } else {
	        $range = explode('-', $range);
	        $c_start = $range[0];
	        $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
	      }
	      $c_end = ($c_end > $end) ? $end : $c_end;
	      	if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
	        header('HTTP/1.1 416 Requested Range Not Satisfiable');
	        header("Content-Range: bytes $start-$end/$size");
	        exit;
	      }
	      $start = $c_start;
	      $end = $c_end;
	      $length = $end - $start + 1;
	      fseek($fp, $start);
	      header('HTTP/1.1 206 Partial Content');
	    }
	    header("Content-Range: bytes $start-$end/$length");
	    header("Content-Length: ".$length);
	    $buffer = 1024 * 8;
	    while(!feof($fp) && ($p = ftell($fp)) <= $end) {
	      	if ($p + $buffer > $end) {
	        	$buffer = $end - $p + 1;
	      	}
	      	set_time_limit(0);
	      	echo fread($fp, $buffer);
	      	flush();
	    }
	    fclose($fp);
	    exit();
	  	echo $response;
	    
	}

	public function getfile() {

		$url = 'http://localhost/laravel/public/api/stream-mp4';
		$response = curl($url);
		$fileSize = $this->getFileSize($url);
		//var_dump($fileSize);
    header('Content-type: video/mp4');
    header("Accept-Ranges: bytes");
    $start = 0;  
    $end = $length - 1; 
    if (isset($_SERVER['HTTP_RANGE'])) {
        $c_start = $start;
        $c_end = $end;
        list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
        if (strpos($range, ',') !== false) {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header("Content-Range: bytes $start-$end/$size");
        exit;
      }
      if ($range == '-') {
        $c_start = $size - substr($range, 1);
      } else {
        $range = explode('-', $range);
        $c_start = $range[0];
        $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
      }
      $c_end = ($c_end > $end) ? $end : $c_end;
        if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header("Content-Range: bytes $start-$end/$size");
        exit;
      }
      $start = $c_start;
      $end = $c_end;
      $length = $end - $start + 1;
      fseek($fp, $start);
      header('HTTP/1.1 206 Partial Content');
    }
    header("Content-Range: bytes $start-$end/$length");
    header("Content-Length: ".$length);
    $buffer = 1024 * 8;
    while(!feof($fp) && ($p = ftell($fp)) <= $end) {
        if ($p + $buffer > $end) {
            $buffer = $end - $p + 1;
        }
        set_time_limit(0);
        echo fread($fp, $buffer);
        flush();
    }
    fclose($fp);
    exit();
    echo $response;
		?>
	<?php }

	public function stream_m3u8() {
		//$url = 'http://localhost/download-file/api/getfile';
		$url = 'https://play.chongmong.xyz/pink/pink.m3u8';
		?>
		<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
		<video style="width: 100%;height: auto;" id="video" muted="" controls></video>
		<script>
		  var video = document.getElementById('video');
		  if(Hls.isSupported()) {
			var hls = new Hls();
			hls.loadSource('<?php echo $url;?>');
			hls.attachMedia(video);
			hls.on(Hls.Events.MANIFEST_PARSED,function() {
			  video.play();
		  });
		 }
		  else if (video.canPlayType('application/vnd.apple.mpegurl')) {
			video.src = '<?php echo $url;?>';
			video.addEventListener('canplay',function() {
			  video.play();
			});
		  }
		</script>
	<?php }

	public function getip() {
		$data = curl('https://st27850.ispot.cc/api/public/getip');
		var_dump($data);
	}

}