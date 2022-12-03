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
		$filelist = ftp_nlist($this->conn, "/public_html/videos/s1/".$folder);
		ftp_close($this->conn);
		var_dump('<pre>');
		var_dump($filelist);
		var_dump('</pre>');
	}
}