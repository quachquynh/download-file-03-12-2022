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
		$tokenPath = 'secret_client.json';
		$this->client = new Client();
		$this->client->setApplicationName("Api PHP");
		$this->client->setDeveloperKey("AIzaSyCbuQ4pbc3smAXdkO9tDsb7g1s4wYE9FIU");  
		$this->client->setClientId('252416329742-kd16oo11hivedtada46udn21nkomk5n1.apps.googleusercontent.com');
		$this->client->setClientSecret('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');
		$this->client->setRedirectUri('http://localhost/download-file/google-drive/token');
		$this->client->setAccessType('offline');
		$this->client->setScopes(array('https://www.googleapis.com/auth/drive'));
		$this->client->setAuthConfig($tokenPath);
		$this->client->setAccessToken($_SESSION['access_token']);
	}

	public function getClient()
	{
	    $this->client = new Client();
	    $this->client->setApplicationName('Google Drive API PHP Quickstart');
	    $this->client->setScopes(\Google_Service_Drive::DRIVE);
	    $this->client->setAuthConfig('secret_client.json');
	    $this->client->setAccessType('offline');
	    $this->client->setIncludeGrantedScopes(true);
	    
	    // Load previously authorized credentials from a file.
	    $tokenPath = ROOTPATH.'\\token\\token.json';

	    if (file_exists($tokenPath)) {
	        $_SESSION['access_token'] = json_decode(file_get_contents($tokenPath), true);
	        var_dump($_SESSION['access_token']);
	        if(isset($_SESSION['access_token'])){
			    $this->client->setAccessToken($_SESSION['access_token']);

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
		$this->client->setAccessToken($_SESSION['access_token']);
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

				var_dump($file_name);
			

				$client = new Client();
				$client->setAccessToken($_SESSION['access_token']);
				$service = new Drive($client);
				//Insert a file
			    $file = new DriveFile( array('parents' => array('1_8ePoN4kEz4SnIzuO8Isl1acsUFSc62t') ));
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

	public function listFiles() {
		$this->client->setAccessToken($_SESSION['access_token']);
		$service = new Drive($this->client);
		$optParams = array(
		  'pageSize' => 100,
		  'fields' => 'nextPageToken, files(id, name, mimeType, parents)',
		  'q' => '"1OXiM8QJ2rZ0Teny_mQ01B84qz-QzD_IC" in parents',
		);
		$results = $service->files->listFiles($optParams);
	    // menampilkan list file
	    echo "<ul>";
	    foreach ($results->getFiles() as $file) {
	        echo "<li><a href='download.php?code=&id=".$file->getID()."'>".$file->getName()."</a></li>";
	    }
	    echo "</ul>";
	    $content = $service->files->get("1dXH0NU79molxKadJxG-ClgbmLszH48Iv", array("alt" => "media"));
 
	    while (!$content->getBody()->eof()) {
	        echo $content->getBody()->read(1024);
	    }
	}

	public function token() {
		if (isset($_GET['code'])) {
			$this->client->authenticate($_GET['code']);
			$token_json = $this->client->getAccessToken();
			//echo json_encode($token);
			$_SESSION['access_token'] = $token_json['access_token'];
			$this->accessToken = $_SESSION['access_token'];
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