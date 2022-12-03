<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Core\DB;
use YouTube\YouTubeDownloader;
use YouTube\Exception\YouTubeException;
use Google\Client;
use App\Controllers\Tools\GoogleDrive;

class Youtube extends Controller {

	public function youtube_download($vid) {
		$data = getCurl("https://youtube.com/get_video_info?video_id=" . $vid);
		parse_str($data, $info);

		$youtube = new YouTubeDownloader();

		try {
		    $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=".$vid);
		    $info = $downloadOptions->getAllFormats();
		    if ($downloadOptions->getAllFormats()) {
		    	var_dump("<pre>");
		    	var_dump($downloadOptions->getInfo());
		        foreach($info as $item) {
		        	echo $item->quality.'===>'.'<a href="'.$item->url.'">'.$item->url.'</a><br/>';
		        }
		        var_dump("</pre>");
		    } else {
		        echo 'No links found';
		    }

		} catch (YouTubeException $e) {
		    echo 'Something went wrong: ' . $e->getMessage();
		}
	}

	public function get_data() {
		new GoogleDrive();

		$client = new Client();
		$client->setAccessToken($_SESSION['access_token']);
		$service = new \Google\Service\YouTube\Video($client);
		

		$youtube = new YouTubeDownloader();
		$result = $youtube->getDownloadLinks("CHDt04DSfUk");
		var_dump("<pre>");
		var_dump($result);
		var_dump("</pre>");
	}
}