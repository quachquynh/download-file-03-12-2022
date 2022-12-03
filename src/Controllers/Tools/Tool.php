<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Models\Product;
use App\Core\DB;

class Tool extends Controller {

	public function getTitle($string) {
	    preg_match_all("/<h1[\s]+[^>]*?class[\s]?=[\s\"\']+"."(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/h1>/", $string, $matches);
	    return $matches[0];
	}

	public function getBody($string) {
	    preg_match_all("/<div[\s]+[^>]*?class[\s]?=[\s\"\']+"."(product-footer)[\"\']+.*?>"."([^<]+|.*?)?<\/div>/", $string, $matches);
	    return $matches;
	}

	public function crawl_web() {
		$url = 'https://maycongcuphucthang.vn/product/https-zalo-me-0974104086-3/';
		$string = file_get_contents($url);
		$txt = $this->getTitle($string);
		$body = $this->getBody($string);
		var_dump($body);
	}
}