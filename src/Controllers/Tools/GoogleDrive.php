<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

/*
 * @document https://github.com/SM4991/php-google-drive/blob/master/upload-drive.php
 */
class GoogleDrive extends Controller {

	protected $client;
	protected $accessToken;
	protected $token_json;
	protected $tokenPath;
	protected $service;

	public function __construct() {
		session_start();
		$tokenPath = 'client_secret.json';
		$this->client = new Client();
		$this->client->setApplicationName("Api PHP");
		$this->client->setDeveloperKey("AIzaSyCbuQ4pbc3smAXdkO9tDsb7g1s4wYE9FIU");  
		//$this->client->setClientId('252416329742-kd16oo11hivedtada46udn21nkomk5n1.apps.googleusercontent.com');
		//$this->client->setClientSecret('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');
		$this->client->setRedirectUri('http://localhost/download-file/google-drive/token');
		$this->client->setAccessType('offline');
		$this->client->setScopes(array('https://www.googleapis.com/auth/drive'));
		$this->client->setAuthConfig($tokenPath);
		//$this->client->setAccessToken($this->accessToken);
	}

	public function getClient()
	{
	    $this->client = new Client();
	    $this->client->setApplicationName('Google Drive API PHP Quickstart');
	    $this->client->setScopes(\Google_Service_Drive::DRIVE);
	    $this->client->setAuthConfig('client_secret.json');
	    $this->client->setAccessType('offline');
	    $this->client->setIncludeGrantedScopes(true);
	    
	    $tokenPath = ROOTPATH.'\\token\\token.json';

	    if (file_exists($tokenPath)) {
	        $json_token = json_decode(file_get_contents($tokenPath), true);
	        $_SESSION['token_array'] = $json_token;

	        if(isset($json_token)){
			    $this->client->setAccessToken($json_token);

			    // Refresh the token if it's expired.
			    if ($this->client->isAccessTokenExpired()) {
			        $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
			        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
			    }
		        //print_r($client->getAccessToken());die;
			    $response = ['success' => 1, 'client' => $this->client];
			    return $response;
		    }
	    }
	    else {
	    	// Request authorization from the user.
	        $authUrl = $this->client->createAuthUrl();

	        $html = "Open the following link in your browser<br>";
	        $html .= '<a href="'.$authUrl.'">Click Here</a>';
		    $response = ['success' => 0, 'html' => $html];
		    return $response;
	    }
        
	}

	public function auth() {
		$data = $this->getClient();
		
		if($this->accessToken == NULL) {
			var_dump($data);
		}
		else {
			echo 'Auth!';
		}
	}

	public function createFolder()
	{
		$token = $this->getClient();
		$this->client = new Client();
		$this->client->setAccessToken($_SESSION['token_array']);
		$service = new Drive($this->client);
		// Create Test folder
		$fileMetadata = new DriveFile(array(
		    'name' => 'Test',
		    'mimeType' => 'application/vnd.google-apps.folder'));
		$file = $service->files->create($fileMetadata, array(
		    'fields' => 'id'));
		printf("<br>Folder ID: %s<br>", $file->id);
	}

	public function upload() {

		if($_SERVER['REQUEST_METHOD'] === 'POST') {

			if(isset($_POST['btn-submit'])) {

				$file_tmp  = $_FILES["filename"]["tmp_name"];
				$file_type = $_FILES["filename"]["type"];
				$file_name = basename($_FILES["filename"]["name"]);

				$file_path = ROOTPATH.'\\public\\google\\'.$file_name;
				move_uploaded_file($file_tmp, $file_path);
				$file_data = file_get_contents($file_path);

				// Fatal error: Uncaught Error: Call to a member function setAccessToken() google drive ==> Phai dung new Client
				$this->client = new Client();
				$this->client->setAccessToken($_SESSION['token_array']);
				$service = new Drive($this->client);
				//Insert a file
			    $file = new DriveFile( array('parents' => array('19tRk3hxO3GmLClfb_DsWRuOZ8mOTuy1i') ));
			    $file->setName($file_name);
			    $file->setDescription('A test document');
			    //$file->setMimeType('application/zip');
			    //$file->setMimeType('application/x-httpd-php');

			    $createdFile = $service->files->create($file, array(
			          'data' => $file_data,
			          //'mimeType' => '',
			          'uploadType' => 'multipart'
			        ));

			    print_r($createdFile);
		    }
		}
		else {
			$this->view("/google-drive/upload");
		}
	}


	public function listFiles($id) {
		//$id = '1K4wMLK8hiHMmmj0kEAOa8F7LVi5__psl';
		// Do not delete
		if($_SESSION['token_array'] == NULL || empty($_SESSION['token_array'])) {
			$this->client = $this->getClient();
		}
		else {
			//$this->client = new Client();
			$this->client->setAccessToken($_SESSION['token_array']);
			$service = new Drive($this->client);
			$optParams = array(
			  'pageSize' => 1000,
			  'fields' => 'nextPageToken, files(id, name, mimeType, parents)',
			  'q' => '"'.$id.'" in parents',
			);
			$results = $service->files->listFiles($optParams);
		    echo "<ul>";
		    foreach ($results->getFiles() as $file) {
		        echo "<li><a href='".ROOTURL."/google-drive/download/".$file->getID()."'>".$file->getID()."</a> ==> <a href='https://drive.google.com/uc?export=download&id=".$file->getID()."' target='_blank'> " .$file->getID()."</a></li>";
		    }
		    echo "</ul>";
		}
	}

	public function download($id, $name) {
		$this->client->setAccessToken($_SESSION['token_array']);
		$service = new Drive($this->client);
		$content = $service->files->get($id, array("alt" => "media"));
	    // Download a file.
		$handle = fopen("C:\\ffmpeg\\video_in\\".$name.".mp4", "w+");
		while (!$content->getBody()->eof()) { 
		    fwrite($handle, $content->getBody());
		}
		fclose($handle);
		echo "success";
	}

	public function stream() {
		$path = "public/media/video.mp4";
		  if ($fp = fopen($path, "rb")) {
		    $size = filesize($path); 
		    $length = $size;
		    $start = 0;  
		    $end = $size - 1; 
		    header('Content-type: video/mp4');
		    header("Accept-Ranges: 0-$length");
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
		    header("Content-Range: bytes $start-$end/$size");
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
		  } else {
		    die('file not found');
		  }
	}

	public function token() {
		if (isset($_GET['code'])) {
			$this->client->authenticate($_GET['code']);
			$token_json = $this->client->getAccessToken();
			//echo json_encode($token);
			$this->accessToken = $token_json['access_token'];
			$this->accessToken = $this->accessToken;
			var_dump($this->accessToken);

		  	$folderPath = 'token/';
		  	if (!file_exists($folderPath)) {
	        	mkdir($folderPath);
	    	}
	    	$filename = 'token.json';
	    	file_put_contents($folderPath.$filename, json_encode($token_json));
		}

	}

	public function googlePhoto() {
		$url = 'https://www.googleapis.com/drive/v3/files/1hHsuJSbt3UrGwGVdyjAhZdQ3gA7_DEqO?alt=media&key=AIzaSyCkSR4mnEU0dSXPFMbOXFCHrqLijV6e6iU';
		$data = file_get_contents($url);
		var_dump($data);
	}

	

}