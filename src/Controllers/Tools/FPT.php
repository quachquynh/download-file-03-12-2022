<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;

class FPT extends Controller {

	protected $conn;

	public function __construct() {
		$ftp_conn = ftp_connect(FPT_SERVER) or die("Could not connect");
		$this->conn = $ftp_conn;
		ftp_login($this->conn, FPT_USER, FPT_PASS);
	}

	public function upload($folder) {

		$dir = "C:\\ffmpeg\\video_out\\".$folder."\\";
		$names = scandir($dir);

		$filelist = ftp_nlist($this->conn, "/public_html/videos/s1/".$folder);

		foreach($names as $name) {

			// local_file
			$local_file = $dir.$name;

			// remote_file
	 		$remote_file = "/public_html/videos/s1/".$folder."/".$name;

			// Error: Warning: ftp_put (): PORT command successful
			ftp_pasv($this->conn, true);

			if (ftp_put($this->conn, $remote_file, $local_file, FTP_BINARY)) {
			 	echo "successfully uploaded $remote_file\n";
			} else {
			 	echo "There was a problem while uploading $remote_file\n";
			}	

		}

		ftp_close($this->conn);
		
	}

	public function list($folder) {
		$filelist = ftp_nlist($this->conn, "/public_html/videos/".$folder);
		foreach($filelist as $file) {
			$res = ftp_size($this->conn, $file);
			var_dump($res);
		}
		ftp_close($this->conn);
		var_dump('<pre>');
		
		var_dump('</pre>');
	}

	public function fpt_upload($number) {
		$root = 's1';
		$folder = $number;
		$dir = 'C:\\ffmpeg\\video_out\\'.$folder.'\\';
		$files = scandir($dir);
		foreach($files as $file) {
			$stat = filesize($dir.$file);
			$local_file = $dir.$file;
			if($stat < 0) {
			}
			else {
	            uploadFTP($root, $folder, $local_file, $file);
			}
			
            
            //uploadFTP($root, $folder, $local_file, $name);
		}

		/*if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (isset($_POST['btn-submit'])) {
				    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
				        $sourcePath = $_FILES['userImage']['tmp_name'];
				        $local_file = ROOTPATH."\\public\\media\\" . $_FILES['userImage']['name'];
				        var_dump($local_file);
				        if (move_uploaded_file($sourcePath, $local_file)) {
				            echo 'Info:<br/>';
				            //echo formatBytes(filesize($file)).'<br/>';
				            $ex = explode("\\", $local_file);
				            $name = end($ex);
				            echo $name.'<br/>';
				            
				            uploadFTP($root, $folder, $local_file, $name);
				        }
				    }
				    $i++;
			}
		}
		else {
			$this->view("/fpt/upload");
		}*/
	}
}