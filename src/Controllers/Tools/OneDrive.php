<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use Google\Client;
use Google\Service\Drive;

class OneDrive extends Controller {

	public function onedrive($url) {
		//$url = 'https://1drv.ms/v/s!Ar15aKazOnCrgVanCkqB4ePxhU_B';
	    $ch = curl_init();
	    $timeout = 10;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    $htmlf = curl_exec($ch);
	    curl_close($ch);
	    $onedrivecurlx = $htmlf;
	    $pattern='/<\s*meta\s+property="og:([^"]+)"\s+content="([^"]*)/i';
	    if(preg_match_all($pattern, $onedrivecurlx, $out)) {
	        $dfikkrr = array_combine($out[1], $out[2]);
	    } else {
	        $dfikkrr = array();
	    }
	    $posterigddd = $dfikkrr['image'];
	    $vurlkf = html_entity_decode(str_ireplace('com/redir', 'com/download', $dfikkrr['url']));
	    $si = [];
	    $si['url'] = $vurlkf;
	    $si['img'] = $posterigddd;
	    var_dump($si['url']);
	    return $si;
	}
}