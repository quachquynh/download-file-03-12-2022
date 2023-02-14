<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use Char0n\FFMpegPHP\Movie;
use Char0n\FFMpegPHP\Adapters\FFMpegMovie as ffmpeg_movie;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Streaming\Representation;

class FFmpeg extends Controller {

	public function run($foldername, $filename, $extension, $root) {
		
		/*
		 * Link: http://localhost/download-file/ffmpeg/35/jp
		 */

		$create_folder_fpt = 0;

		$folder_out = "C:\\ffmpeg\\video_out\\".$foldername;
		// connect and login to FTP server
		$ftp_conn = ftp_connect(FPT_SERVER) or die("Could not connect ".FPT_SERVER);
		$login = ftp_login($ftp_conn, FPT_USER, FPT_PASS);

		$dir = "/public_html/videos/".$root."/".$foldername;

		try {
			if(ftp_mkdir($ftp_conn, $dir) && $create_folder_fpt == 1) {
				mkdir($folder_out);
				ffmpeg_run($foldername, $filename, $extension);
			}
			elseif($create_folder_fpt == 0) {
				mkdir($folder_out);
				ffmpeg_run($foldername, $filename, $extension);
			}
		}
		catch(Exception $e) {
		  echo 'Message: ' .$e->getMessage();
		}

		ftp_close($ftp_conn);
	}

	public function dash($filename) {
		echo shell_exec('C:\\ffmpeg\\bin\\ffmpeg.exe -re -i C:\\ffmpeg\\video_in\\'.$filename.'.mp4 -map 0 -map 0 -c:a aac -c:v libx264 -b:v:0 800k -b:v:1 300k -s:v:1 640x360 -profile:v:1 baseline -profile:v:0 main -bf 1 -keyint_min 120 -g 120 -sc_threshold 0 -b_strategy 0 -ar:a:1 22050 -use_timeline 1 -use_template 1 -window_size 5 -adaptation_sets "id=0,streams=v id=1,streams=a" -f dash E:\\xampp\\htdocs\\download-file\\public\\dash\\out.mpd');
	}

	public function capture($filename)
	{
		echo shell_exec("C:\\ffmpeg\\bin\\ffmpeg.exe -ss 01:23:45 -i C:\\ffmpeg\\video_in\\".$filename.".mp4 -frames:v 1 C:\\ffmpeg\\thumbnail\\output.jpg");
	}

	public function convert_video() {
		$config = [
		    'ffmpeg.binaries'  => 'C:/ffmpeg/bin/ffmpeg.exe',
		    'ffprobe.binaries' => 'C:/ffmpeg/bin/ffprobe.exe',
		    'timeout'          => 3600, // The timeout for the underlying process
		    'ffmpeg.threads'   => 5,   // The number of threads that FFmpeg should use
		];

		$log = new Logger('FFmpeg_Streaming');
		$log->pushHandler(new StreamHandler('var/log/ffmpeg-streaming.log'));
		    
		$ffmpeg = \Streaming\FFMpeg::create($config, $log);

		$video = $ffmpeg->open('public/media/test.mp4');
		//$r_360p  = (new Representation)->setKiloBitrate(276)->setResize(640, 360);
		//$r_480p  = (new Representation)->setKiloBitrate(750)->setResize(854, 480);
		$r_720p  = (new Representation)->setKiloBitrate(2048)->setResize(1280, 720);
		//[$r_360p, $r_480p, $r_720p]
		$video->hls()
		    ->x264()
		    ->addRepresentations([$r_720p])
		    ->save("public/out/hls-stream.mpd");
	}

	public function hls_stream() {
		$this->view("hls-stream");
	}

	
}